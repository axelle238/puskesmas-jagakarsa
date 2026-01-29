<?php

namespace App\Livewire\Laporan;

use App\Models\RekamMedis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LaporanPenyakit extends Component
{
    public $bulan;
    public $tahun;

    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }

    public function render()
    {
        // Top 10 Penyakit Terbanyak
        // Group by diagnosis_kode
        $laporan = RekamMedis::select('diagnosis_kode', 'asesmen', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $this->bulan)
            ->whereYear('created_at', $this->tahun)
            ->whereNotNull('diagnosis_kode')
            ->groupBy('diagnosis_kode', 'asesmen')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('livewire.laporan.laporan-penyakit', [
            'laporan' => $laporan
        ])->layout('components.layouts.admin', ['title' => 'Laporan Penyakit (LB1)']);
    }
}