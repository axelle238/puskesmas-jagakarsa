<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Antrian;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Poli;
use Carbon\Carbon;

class Dasbor extends Component
{
    public function render()
    {
        // Statistik Hari Ini
        $totalAntrianHariIni = Antrian::whereDate('tanggal_antrian', Carbon::today())->count();
        $antrianMenunggu = Antrian::whereDate('tanggal_antrian', Carbon::today())
                            ->where('status', 'menunggu')
                            ->count();
        
        $totalPasien = Pasien::count();
        $dokterAktif = Pegawai::whereNotNull('sip')->count(); // Asumsi yang punya SIP adalah dokter

        // Antrian Terbaru
        $antrianTerbaru = Antrian::with(['pasien', 'poli'])
                            ->whereDate('tanggal_antrian', Carbon::today())
                            ->latest()
                            ->take(5)
                            ->get();

        return view('livewire.dasbor', [
            'totalAntrianHariIni' => $totalAntrianHariIni,
            'antrianMenunggu' => $antrianMenunggu,
            'totalPasien' => $totalPasien,
            'dokterAktif' => $dokterAktif,
            'antrianTerbaru' => $antrianTerbaru
        ])->layout('components.layouts.admin', ['title' => 'Dasbor Utama']);
    }
}