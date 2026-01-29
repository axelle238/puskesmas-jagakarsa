<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Antrian;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Poli;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dasbor extends Component
{
    public function render()
    {
        // Statistik Hari Ini
        $today = Carbon::today();
        $totalAntrianHariIni = Antrian::whereDate('tanggal_antrian', $today)->count();
        $antrianMenunggu = Antrian::whereDate('tanggal_antrian', $today)
                            ->where('status', 'menunggu')
                            ->count();
        
        $totalPasien = Pasien::count();
        $dokterAktif = Pegawai::whereNotNull('sip')->count();

        // Antrian Terbaru
        $antrianTerbaru = Antrian::with(['pasien', 'poli'])
                            ->whereDate('tanggal_antrian', $today)
                            ->latest()
                            ->take(5)
                            ->get();

        // Data Chart Kunjungan Mingguan (7 Hari Terakhir)
        $chartData = [];
        $chartLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Antrian::whereDate('tanggal_antrian', $date)->count();
            
            $chartLabels[] = $date->isoFormat('dddd'); // Senin, Selasa...
            $chartData[] = $count;
        }

        return view('livewire.dasbor', [
            'totalAntrianHariIni' => $totalAntrianHariIni,
            'antrianMenunggu' => $antrianMenunggu,
            'totalPasien' => $totalPasien,
            'dokterAktif' => $dokterAktif,
            'antrianTerbaru' => $antrianTerbaru,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ])->layout('components.layouts.admin', ['title' => 'Dasbor Utama']);
    }
}
