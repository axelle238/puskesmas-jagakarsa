<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Presensi;
use App\Models\Tagihan;
use Carbon\Carbon;
use Livewire\Component;

class Dasbor extends Component
{
    public function render()
    {
        $hariIni = Carbon::today();

        // 1. Statistik Kunjungan Pasien
        $totalPasienHariIni = Antrian::whereDate('tanggal_antrian', $hariIni)->count();
        $sedangDilayani = Antrian::whereDate('tanggal_antrian', $hariIni)
            ->whereIn('status', ['dipanggil', 'diperiksa']) 
            ->count();
        
        // 2. Statistik Keuangan (Pendapatan Hari Ini)
        $pendapatanHariIni = Tagihan::whereDate('created_at', $hariIni)
            ->where('status_bayar', 'lunas')
            ->sum('jumlah_bayar');

        // 3. Statistik Kepegawaian (Presensi Hari Ini) - BARU
        $pegawaiHadir = Presensi::whereDate('tanggal', $hariIni)
            ->where('status', 'hadir')
            ->count();
            
        // 4. Statistik Farmasi (Stok Menipis)
        $obatMenipis = Obat::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->limit(5)
            ->get();

        // 5. Antrian Terakhir (Live Monitoring)
        $antrianTerbaru = Antrian::with(['pasien', 'poli'])
            ->whereDate('tanggal_antrian', $hariIni)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // 6. Grafik Kunjungan 7 Hari
        $grafikLabel = [];
        $grafikData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $grafikLabel[] = $tgl->isoFormat('dddd'); 
            $grafikData[] = Antrian::whereDate('tanggal_antrian', $tgl)->count();
        }

        return view('livewire.dasbor', [
            'totalPasienHariIni' => $totalPasienHariIni,
            'sedangDilayani' => $sedangDilayani,
            'pendapatanHariIni' => $pendapatanHariIni,
            'pegawaiHadir' => $pegawaiHadir, // Kirim data ke view
            'obatMenipis' => $obatMenipis,
            'antrianTerbaru' => $antrianTerbaru,
            'grafikLabel' => $grafikLabel,
            'grafikData' => $grafikData
        ])->layout('components.layouts.admin', ['title' => 'Executive Dashboard']);
    }
}