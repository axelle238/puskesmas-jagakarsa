<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Tagihan; // Asumsi ada model Tagihan
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
            ->whereIn('status', ['dipanggil', 'diperiksa']) // Status aktif
            ->count();
        
        // Asumsi logic pendapatan: Sum tagihan yang lunas hari ini
        // Jika tabel tagihan belum ada/siap, kita pakai placeholder 0
        $pendapatanHariIni = 0; 
        if (class_exists(Tagihan::class)) {
            // $pendapatanHariIni = Tagihan::whereDate('created_at', $hariIni)->where('status', 'lunas')->sum('total_biaya');
        }

        // Stok Obat Menipis (Warning)
        $obatMenipis = Obat::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->limit(5)
            ->get();

        // Antrian Terakhir (Tabel Ringkas)
        $antrianTerbaru = Antrian::with(['pasien', 'poli'])
            ->whereDate('tanggal_antrian', $hariIni)
            ->orderBy('id', 'desc') // Yang baru daftar di atas
            ->limit(5)
            ->get();

        return view('livewire.dasbor', [
            'totalPasienHariIni' => $totalPasienHariIni,
            'sedangDilayani' => $sedangDilayani,
            'pendapatanHariIni' => $pendapatanHariIni,
            'obatMenipis' => $obatMenipis,
            'antrianTerbaru' => $antrianTerbaru
        ])->layout('components.layouts.admin', ['title' => 'Dasbor Utama']);
    }
}