<?php

namespace App\Livewire\Medis;

use App\Models\Antrian;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\TindakanMedis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Form Pemeriksaan Medis')]
class Pemeriksaan extends Component
{
    public $antrian;
    public $pasien;
    public $riwayatMedis = []; // Properti Baru
    
    // SOAP Data
    public $keluhan_utama;
    public $riwayat_penyakit;
    public $tanda_vital = [
        'sistole' => '', 'diastole' => '', 'suhu' => '', 
        'nadi' => '', 'rr' => '', 'bb' => '', 'tb' => ''
    ];
    public $diagnosis_kode; // ICD-10
    public $diagnosis_text; // Asesmen
    public $plan_terapi;

    // Resep Obat (Dynamic Input)
    public $list_obat = [];
    public $resep_input = []; 

    // Tindakan (Dynamic Input)
    public $list_tindakan = []; 
    public $tindakan_input = []; 

    // UI State
    public $tabAktif = 'periksa'; // 'periksa' atau 'riwayat'

    public function mount($idAntrian)
    {
        $this->antrian = Antrian::with(['pasien', 'poli', 'jadwal.dokter'])->findOrFail($idAntrian);
        $this->pasien = $this->antrian->pasien;

        if ($this->antrian->status !== 'diperiksa') {
            return redirect()->route('medis.antrian');
        }

        // Ambil Riwayat Medis Sebelumnya (Descending)
        $this->riwayatMedis = RekamMedis::where('id_pasien', $this->pasien->id)
                                ->with(['dokter.pengguna', 'poli', 'resepDetail'])
                                ->latest()
                                ->get();

        $this->list_obat = Obat::where('stok_saat_ini', '>', 0)->get();
        $this->list_tindakan = TindakanMedis::where('id_poli', $this->antrian->id_poli)->get();

        $this->tambahResep();
    }

    public function tambahResep()
    {
        $this->resep_input[] = ['id_obat' => '', 'jumlah' => 1, 'aturan_pakai' => '3x1 Sesudah Makan'];
    }

    public function hapusResep($index)
    {
        unset($this->resep_input[$index]);
        $this->resep_input = array_values($this->resep_input);
    }

    public function tambahTindakan()
    {
        $this->tindakan_input[] = ['id_tindakan' => '', 'catatan' => ''];
    }

    public function hapusTindakan($index)
    {
        unset($this->tindakan_input[$index]);
        $this->tindakan_input = array_values($this->tindakan_input);
    }

    public function simpanPemeriksaan()
    {
        $this->validate([
            'keluhan_utama' => 'required',
            'diagnosis_text' => 'required',
        ]);

        DB::transaction(function () {
            // 1. Simpan Rekam Medis Header
            $rm = RekamMedis::create([
                'id_pasien' => $this->pasien->id,
                'id_dokter' => $this->antrian->jadwal->id_dokter,
                'id_poli' => $this->antrian->id_poli,
                'id_antrian' => $this->antrian->id,
                'keluhan_utama' => $this->keluhan_utama,
                'riwayat_penyakit' => $this->riwayat_penyakit,
                'subjektif' => $this->keluhan_utama . "\n" . $this->riwayat_penyakit,
                'objektif' => json_encode($this->tanda_vital),
                'asesmen' => $this->diagnosis_text,
                'diagnosis_kode' => $this->diagnosis_kode,
                'plan' => $this->plan_terapi,
            ]);

            // 2. Simpan Detail Resep
            foreach ($this->resep_input as $item) {
                if (!empty($item['id_obat'])) {
                    $rm->resepDetail()->attach($item['id_obat'], [
                        'jumlah' => $item['jumlah'],
                        'aturan_pakai' => $item['aturan_pakai'],
                        'status_pengambilan' => 'menunggu'
                    ]);
                    $obat = Obat::find($item['id_obat']);
                    $obat->decrement('stok_saat_ini', $item['jumlah']);
                }
            }

            // 3. Simpan Detail Tindakan
            foreach ($this->tindakan_input as $item) {
                if (!empty($item['id_tindakan'])) {
                    $tindakan = TindakanMedis::find($item['id_tindakan']);
                    $rm->tindakanDetail()->attach($item['id_tindakan'], [
                        'biaya_saat_ini' => $tindakan->tarif,
                        'catatan_tindakan' => $item['catatan'] ?? null
                    ]);
                }
            }

            // 4. Update Status Antrian
            $this->antrian->update([
                'status' => 'selesai',
                'waktu_selesai' => now()
            ]);
        });

        session()->flash('pesan', 'Pemeriksaan selesai. Data rekam medis berhasil disimpan.');
        return redirect()->route('medis.antrian');
    }

    public function render()
    {
        return view('livewire.medis.pemeriksaan')
            ->layout('components.layouts.admin');
    }
}