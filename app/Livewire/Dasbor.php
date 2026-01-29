<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Tagihan;
use Carbon\Carbon;
use Livewire\Component;

class Dasbor extends Component
{
    public function render()
    {
        $hariIni = Carbon::today();

        // Statistik Utama
        $totalPasienHariIni = Antrian::whereDate('tanggal_antrian', $hariIni)->count();
        $sedangDilayani = Antrian::whereDate('tanggal_antrian', $hariIni)
            ->whereIn('status', ['dipanggil', 'diperiksa']) 
            ->count();
        
        $pendapatanHariIni = Tagihan::whereDate('created_at', $hariIni)
            ->where('status_bayar', 'lunas')
            ->sum('jumlah_bayar');

        // Stok Obat Menipis
        $obatMenipis = Obat::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->limit(5)
            ->get();

        // Antrian Terakhir
        $antrianTerbaru = Antrian::with(['pasien', 'poli'])
            ->whereDate('tanggal_antrian', $hariIni)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // Data Grafik Kunjungan (7 Hari Terakhir)
        $grafikLabel = [];
        $grafikData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $grafikLabel[] = $tgl->isoFormat('dddd'); // Nama hari
            $grafikData[] = Antrian::whereDate('tanggal_antrian', $tgl)->count();
        }

        return view('livewire.dasbor', [
            'totalPasienHariIni' => $totalPasienHariIni,
            'sedangDilayani' => $sedangDilayani,
            'pendapatanHariIni' => $pendapatanHariIni,
            'obatMenipis' => $obatMenipis,
            'antrianTerbaru' => $antrianTerbaru,
            'grafikLabel' => $grafikLabel,
            'grafikData' => $grafikData
        ])->layout('components.layouts.admin', ['title' => 'Dasbor Utama']);
    }
}
