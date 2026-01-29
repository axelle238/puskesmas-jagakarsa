<?php

namespace App\Livewire\Mutu;

use App\Models\InsidenKeselamatan as ModelInsiden;
use Livewire\Component;
use Livewire\WithPagination;

class InsidenKeselamatan extends Component
{
    use WithPagination;

    public $modalLapor = false;
    
    // Form Input
    public $tanggal_kejadian;
    public $waktu_kejadian;
    public $lokasi_kejadian;
    public $jenis_insiden = 'KNC';
    public $kronologi;
    public $tindakan_segera;
    public $grading_risiko = 'biru';

    public function lapor()
    {
        $this->resetForm();
        $this->modalLapor = true;
    }

    public function simpan()
    {
        $this->validate([
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian' => 'required',
            'lokasi_kejadian' => 'required',
            'kronologi' => 'required',
            'tindakan_segera' => 'required'
        ]);

        ModelInsiden::create([
            'tanggal_kejadian' => $this->tanggal_kejadian,
            'waktu_kejadian' => $this->waktu_kejadian,
            'lokasi_kejadian' => $this->lokasi_kejadian,
            'jenis_insiden' => $this->jenis_insiden,
            'kronologi' => $this->kronologi,
            'tindakan_segera' => $this->tindakan_segera,
            'grading_risiko' => $this->grading_risiko,
            'id_pelapor' => auth()->user()->pegawai->id ?? 1,
            'status_investigasi' => 'belum'
        ]);

        $this->modalLapor = false;
        session()->flash('sukses', 'Laporan insiden berhasil dikirim. Tim Mutu akan segera melakukan investigasi.');
    }

    public function resetForm()
    {
        $this->reset(['tanggal_kejadian', 'waktu_kejadian', 'lokasi_kejadian', 'kronologi', 'tindakan_segera']);
        $this->tanggal_kejadian = date('Y-m-d');
        $this->waktu_kejadian = date('H:i');
    }

    public function render()
    {
        $laporan = ModelInsiden::with('pelapor')->latest()->paginate(10);

        return view('livewire.mutu.insiden-keselamatan', [
            'laporan' => $laporan
        ])->layout('components.layouts.admin', ['title' => 'Insiden Keselamatan Pasien (IKP)']);
    }
}
