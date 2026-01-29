<?php

namespace App\Livewire\Laboratorium;

use App\Models\PermintaanLab;
use App\Models\HasilLab;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Laboratorium')]
class InputHasil extends Component
{
    public $permintaan_terpilih = null;
    
    // Form Hasil (Array Dinamis)
    public $hasil_input = [];

    public function mount()
    {
        // Template Parameter Standar
        $this->hasil_input = [
            ['parameter' => 'Hemoglobin', 'nilai' => '', 'satuan' => 'g/dL', 'rujukan' => '12-16'],
            ['parameter' => 'Leukosit', 'nilai' => '', 'satuan' => '/mm3', 'rujukan' => '5000-10000'],
            ['parameter' => 'Trombosit', 'nilai' => '', 'satuan' => '/mm3', 'rujukan' => '150000-450000'],
        ];
    }

    public function pilihPermintaan($id)
    {
        $this->permintaan_terpilih = PermintaanLab::with('rekamMedis.pasien')->find($id);
    }

    public function simpanHasil()
    {
        if (!$this->permintaan_terpilih) return;

        foreach ($this->hasil_input as $input) {
            if (!empty($input['nilai'])) {
                HasilLab::create([
                    'id_permintaan_lab' => $this->permintaan_terpilih->id,
                    'parameter' => $input['parameter'],
                    'nilai_hasil' => $input['nilai'],
                    'nilai_rujukan' => $input['rujukan'],
                    'satuan' => $input['satuan'],
                ]);
            }
        }

        $this->permintaan_terpilih->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
            // 'id_petugas_lab' => Auth::user()->pegawai->id // Asumsi user adalah pegawai
        ]);

        session()->flash('pesan', 'Hasil laboratorium berhasil disimpan.');
        $this->permintaan_terpilih = null;
    }

    public function render()
    {
        $antrian_lab = PermintaanLab::with(['rekamMedis.pasien', 'dokter.pengguna'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();

        return view('livewire.laboratorium.input-hasil', [
            'antrian_lab' => $antrian_lab
        ])->layout('components.layouts.admin');
    }
}
