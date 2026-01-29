<?php

namespace App\Livewire\Medis;

use App\Models\Antrian;
use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\RekamMedis;
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
    public $resepList = []; // Array of ['id_obat', 'nama_obat', 'jumlah', 'dosis', 'catatan']
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

    // --- LOGIC RESEP ---

    public function tambahObat()
    {
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
                'stok' => $obat->stok_saat_ini, // Untuk validasi visual saja
                'jumlah' => $this->inputResep['jumlah'],
                'dosis' => $this->inputResep['dosis'],
                'catatan' => $this->inputResep['catatan'],
                'harga' => $obat->harga_satuan
            ];

            // Reset input form
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

    // --- SIMPAN REKAM MEDIS ---

    public function simpanPemeriksaan()
    {
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
                'id_dokter' => auth()->user()->pegawai->id ?? 1, // Fallback ID 1 jika testing
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

        // 2. Simpan Detail Resep
        // Hapus detail lama dulu jika edit (sederhana)
        DetailResep::where('id_rekam_medis', $rm->id)->delete();

        foreach ($this->resepList as $resep) {
            DetailResep::create([
                'id_rekam_medis' => $rm->id,
                'id_obat' => $resep['id_obat'],
                'jumlah' => $resep['jumlah'],
                'dosis' => $resep['dosis'],
                'catatan' => $resep['catatan'],
                'harga_satuan_saat_ini' => $resep['harga']
            ]);
            
            // Note: Stok dipotong nanti saat di Farmasi (Proses Resep)
            // Atau dipotong sekarang? Best practice: potong saat farmasi menyiapkan ('book' stock).
            // Di sini kita biarkan stok utuh, nanti farmasi validasi.
        }

        // 3. Update Status Antrian
        $this->antrian->status = 'selesai'; // Atau 'farmasi' jika ada resep
        if (count($this->resepList) > 0) {
            // Logic tambahan: Status antrian bisa jadi 'farmasi' agar muncul di dashboard apoteker
            // Tapi field status enum kita: menunggu, dipanggil, diperiksa, selesai, batal
            // Kita pakai 'selesai' di poli, nanti modul farmasi cari rekam medis hari ini yg punya resep.
            $this->antrian->status = 'selesai';
        }
        $this->antrian->waktu_selesai = now();
        $this->antrian->save();

        session()->flash('sukses', 'Pemeriksaan selesai. Data rekam medis tersimpan.');
        return redirect()->route('medis.antrian');
    }

    public function render()
    {
        $obatList = Obat::orderBy('nama_obat')->get();
        // Riwayat kunjungan sebelumnya
        $riwayatKunjungan = RekamMedis::where('id_pasien', $this->pasien->id)
            ->where('id', '!=', $this->antrian->id) // Exclude current if exists
            ->latest()
            ->get();

        return view('livewire.medis.pemeriksaan', [
            'obatList' => $obatList,
            'riwayatKunjungan' => $riwayatKunjungan
        ])->layout('components.layouts.admin', ['title' => 'Pemeriksaan Medis']);
    }
}
