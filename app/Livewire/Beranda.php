<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\ArtikelEdukasi;
use App\Models\KlasterIlp;
use Carbon\Carbon;
use Livewire\Component;

class Beranda extends Component
{
    public function render()
    {
        // Data Klaster ILP
        $klaster = KlasterIlp::with('poli')->get();

        // Data Artikel Edukasi (3 terbaru)
        $artikel = ArtikelEdukasi::latest()->take(3)->get();

        // Statistik Sederhana Hari Ini
        $totalAntrian = Antrian::whereDate('created_at', Carbon::today())->count();
        $sedangDilayani = Antrian::whereDate('created_at', Carbon::today())
            ->where('status', 'dilayani')
            ->count();
        $sisaAntrian = Antrian::whereDate('created_at', Carbon::today())
            ->where('status', 'menunggu')
            ->count();

        return view('livewire.beranda', [
            'klaster' => $klaster,
            'artikel' => $artikel,
            'totalAntrian' => $totalAntrian,
            'sedangDilayani' => $sedangDilayani,
            'sisaAntrian' => $sisaAntrian,
        ]);
    }
}