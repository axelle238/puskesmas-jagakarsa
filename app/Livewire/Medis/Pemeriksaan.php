<?php

namespace App\Livewire\Medis;

use App\Models\Antrian;
use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Services\BpjsService; // Import Service
use Livewire\Component;

/**
 * Class Pemeriksaan
 * Modul utama Dokter untuk melakukan pemeriksaan pasien (SOAP & Resep).
 */
class Pemeriksaan extends Component
{
    public $antrian;
    public $pasien;
    public $activeTab = 'soap'; // soap, resep, riwayat

    // Form SOAP
    public $keluhan_utama;
    public $riwayat_penyakit;
    public $subjektif;
    public $objektif;
    public $asesmen;
    public $plan;
    public $diagnosis_kode;
    public $tindakan;

    // Form Resep Temporary
    public $resepList = []; 
    public $inputResep = [
        'id_obat' => '',
        'jumlah' => 1,
        'dosis' => '3x1 Sesudah Makan',
        'catatan' => ''
    ];

    public function mount($idAntrian)
    {
        $this->antrian = Antrian::with(['pasien', 'poli'])->findOrFail($idAntrian);
        $this->pasien = $this->antrian->pasien;

        // Cek jika sudah ada rekam medis sebelumnya untuk antrian ini (Resume)
        $rm = RekamMedis::where('id_antrian', $idAntrian)->first();
        if ($rm) {
            if ($this->antrian->status == 'selesai') {
                session()->flash('error', 'Rekam medis ini sudah selesai dan tidak dapat diubah.');
            }

            $this->keluhan_utama = $rm->keluhan_utama;
            $this->riwayat_penyakit = $rm->riwayat_penyakit;
            $this->subjektif = $rm->subjektif;
            $this->objektif = $rm->objektif;
            $this->asesmen = $rm->asesmen;
            $this->plan = $rm->plan;
            $this->diagnosis_kode = $rm->diagnosis_kode;
            $this->tindakan = $rm->tindakan;
        }
    }

    public function tambahObat()
    {
        if ($this->antrian->status == 'selesai') return;

        $this->validate([
            'inputResep.id_obat' => 'required',
            'inputResep.jumlah' => 'required|numeric|min:1',
            'inputResep.dosis' => 'required',
        ]);

        $obat = Obat::find($this->inputResep['id_obat']);
        
        if ($obat) {
            $this->resepList[] = [
                'id_obat' => $obat->id,
                'nama_obat' => $obat->nama_obat,
                'stok' => $obat->stok_saat_ini, 
                'jumlah' => $this->inputResep['jumlah'],
                'dosis' => $this->inputResep['dosis'],
                'catatan' => $this->inputResep['catatan'],
                'harga' => $obat->harga_satuan
            ];

            $this->inputResep = [
                'id_obat' => '',
                'jumlah' => 1,
                'dosis' => '3x1 Sesudah Makan',
                'catatan' => ''
            ];
        }
    }

    public function hapusObat($index)
    {
        unset($this->resepList[$index]);
        $this->resepList = array_values($this->resepList); // Re-index
    }

use App\Models\Tagihan; // Import Model

    // ... (kode sebelumnya)

    public function simpanPemeriksaan(BpjsService $bpjsService)
    {
        if ($this->antrian->status == 'selesai') {
            session()->flash('error', 'Data terkunci. Tidak dapat menyimpan perubahan.');
            return;
        }

        $this->validate([
            'keluhan_utama' => 'required',
            'subjektif' => 'required',
            'asesmen' => 'required', // Diagnosa
        ]);

        // 1. Simpan Header Rekam Medis
        $rm = RekamMedis::updateOrCreate(
            ['id_antrian' => $this->antrian->id],
            [
                'id_pasien' => $this->pasien->id,
                'id_dokter' => auth()->user()->pegawai->id ?? 1,
                'id_poli' => $this->antrian->id_poli,
                'keluhan_utama' => $this->keluhan_utama,
                'riwayat_penyakit' => $this->riwayat_penyakit,
                'subjektif' => $this->subjektif,
                'objektif' => $this->objektif,
                'asesmen' => $this->asesmen,
                'plan' => $this->plan,
                'diagnosis_kode' => $this->diagnosis_kode,
                'tindakan' => $this->tindakan,
                'resep_obat' => count($this->resepList) > 0 ? 'Lihat Detail' : 'Tidak ada resep',
            ]
        );

        // 2. Simpan Detail Resep & Hitung Total Biaya Obat
        DetailResep::where('id_rekam_medis', $rm->id)->delete();
        
        $totalBiayaObat = 0;

        foreach ($this->resepList as $resep) {
            DetailResep::create([
                'id_rekam_medis' => $rm->id,
                'id_obat' => $resep['id_obat'],
                'jumlah' => $resep['jumlah'],
                'dosis' => $resep['dosis'],
                'catatan' => $resep['catatan'],
                'harga_satuan_saat_ini' => $resep['harga']
            ]);
            $totalBiayaObat += ($resep['harga'] * $resep['jumlah']);
        }

        // 3. Generate Tagihan Otomatis
        // Biaya Jasa Medis (Default/Flat dulu untuk MVP, idealnya dari tabel Tindakan)
        $biayaJasa = 15000; 
        // Jika pasien BPJS, total tagihan 0 (ditanggung)
        $totalTagihan = $this->pasien->no_bpjs ? 0 : ($biayaJasa + $totalBiayaObat);

        Tagihan::updateOrCreate(
            ['id_rekam_medis' => $rm->id],
            [
                'no_tagihan' => 'INV-' . date('YmdHis') . '-' . $rm->id,
                'total_biaya' => $totalTagihan,
                'jumlah_bayar' => 0,
                'status_bayar' => $totalTagihan == 0 ? 'lunas' : 'belum_bayar', // BPJS langsung lunas
                'metode_bayar' => $totalTagihan == 0 ? 'bpjs' : null,
            ]
        );

        // 4. Bridging BPJS (Simulasi)
        if ($this->pasien->no_bpjs) {
            $bpjsService->inputTindakan($this->pasien->no_bpjs, [
                'kdDiagnosa' => $this->diagnosis_kode,
                'nmDiagnosa' => $this->asesmen,
                'terapi' => $this->tindakan
            ]);
        }

        // 5. Update Status Antrian
        $this->antrian->status = 'selesai'; 
        $this->antrian->waktu_selesai = now();
        $this->antrian->save();

        session()->flash('sukses', 'Pemeriksaan selesai. Data rekam medis & tagihan berhasil dibuat.');
        return redirect()->route('medis.antrian');
    }

    public function render()
    {
        $obatList = Obat::orderBy('nama_obat')->get();
        $riwayatKunjungan = RekamMedis::where('id_pasien', $this->pasien->id)
            ->where('id', '!=', $this->antrian->id)
            ->latest()
            ->get();

        return view('livewire.medis.pemeriksaan', [
            'obatList' => $obatList,
            'riwayatKunjungan' => $riwayatKunjungan
        ])->layout('components.layouts.admin', ['title' => 'Pemeriksaan Medis']);
    }
}