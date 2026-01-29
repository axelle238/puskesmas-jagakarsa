<?php

namespace App\Livewire\Mutu;

use App\Models\RegisterRisiko as ModelRisiko;
use Livewire\Component;
use Livewire\WithPagination;

class RegisterRisiko extends Component
{
    use WithPagination;

    public $modalInput = false;

    // Form
    public $unit_kerja;
    public $pernyataan_risiko;
    public $penyebab;
    public $dampak;
    public $nilai_kemungkinan = 1;
    public $nilai_dampak = 1;
    public $pengendalian_saat_ini;
    public $rencana_penanganan;

    public function tambah()
    {
        $this->resetForm();
        $this->modalInput = true;
    }

    public function simpan()
    {
        $this->validate([
            'unit_kerja' => 'required',
            'pernyataan_risiko' => 'required',
            'nilai_kemungkinan' => 'required|numeric|min:1|max:5',
            'nilai_dampak' => 'required|numeric|min:1|max:5',
        ]);

        // Hitung skor & tingkat risiko (Risk Matrix Sederhana)
        $skor = $this->nilai_kemungkinan * $this->nilai_dampak;
        $tingkat = 'Rendah';
        if ($skor >= 15) $tingkat = 'Ekstrem';
        elseif ($skor >= 10) $tingkat = 'Tinggi';
        elseif ($skor >= 5) $tingkat = 'Sedang';

        ModelRisiko::create([
            'unit_kerja' => $this->unit_kerja,
            'pernyataan_risiko' => $this->pernyataan_risiko,
            'penyebab' => $this->penyebab,
            'dampak' => $this->dampak,
            'nilai_kemungkinan' => $this->nilai_kemungkinan,
            'nilai_dampak' => $this->nilai_dampak,
            'tingkat_risiko' => $tingkat,
            'pengendalian_saat_ini' => $this->pengendalian_saat_ini,
            'rencana_penanganan' => $this->rencana_penanganan
        ]);

        $this->modalInput = false;
        session()->flash('sukses', 'Risiko berhasil diidentifikasi.');
    }

    public function resetForm()
    {
        $this->reset(['unit_kerja', 'pernyataan_risiko', 'penyebab', 'dampak', 'pengendalian_saat_ini', 'rencana_penanganan']);
        $this->nilai_kemungkinan = 1;
        $this->nilai_dampak = 1;
    }

    public function render()
    {
        $risiko = ModelRisiko::latest()->paginate(10);

        return view('livewire.mutu.register-risiko', [
            'daftarRisiko' => $risiko
        ])->layout('components.layouts.admin', ['title' => 'Register Risiko']);
    }
}
