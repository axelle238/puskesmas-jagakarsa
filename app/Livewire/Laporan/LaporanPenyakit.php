<?php

namespace App\Livewire\Laporan;

use App\Models\RekamMedis;
use Livewire\Component;
use Livewire\Attributes\Title;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Title('Laporan 10 Besar Penyakit')]
class LaporanPenyakit extends Component
{
    public $bulan;
    public $tahun;

    public function mount()
    {
        $this->bulan = Carbon::now()->month;
        $this->tahun = Carbon::now()->year;
    }

    public function render()
    {
        // Query Agregat untuk menghitung frekuensi diagnosa
        $penyakits = RekamMedis::select('diagnosis_kode', 'asesmen', DB::raw('count(*) as total'))
            ->whereYear('created_at', $this->tahun)
            ->whereMonth('created_at', $this->bulan)
            ->whereNotNull('diagnosis_kode')
            ->groupBy('diagnosis_kode', 'asesmen')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('livewire.laporan.laporan-penyakit', [
            'penyakits' => $penyakits
        ])->layout('components.layouts.admin');
    }
}
