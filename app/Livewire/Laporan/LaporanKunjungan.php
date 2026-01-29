<?php

namespace App\Livewire\Laporan;

use App\Models\Antrian;
use App\Models\Poli;
use Carbon\Carbon;
use Livewire\Component;

class LaporanKunjungan extends Component
{
    public $tanggal_mulai;
    public $tanggal_selesai;

    public function mount()
    {
        $this->tanggal_mulai = Carbon::today()->format('Y-m-d');
        $this->tanggal_selesai = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Rekap per Poli
        $laporanPoli = Poli::withCount(['antrian as jumlah_kunjungan' => function($q) {
            $q->whereBetween('tanggal_antrian', [$this->tanggal_mulai, $this->tanggal_selesai])
              ->where('status', '!=', 'batal');
        }])->get();

        // Total Kunjungan
        $totalKunjungan = Antrian::whereBetween('tanggal_antrian', [$this->tanggal_mulai, $this->tanggal_selesai])
            ->where('status', '!=', 'batal')
            ->count();

        // Rekap Harian (Grafik sederhana)
        $rekapHarian = Antrian::selectRaw('DATE(tanggal_antrian) as tanggal, count(*) as total')
            ->whereBetween('tanggal_antrian', [$this->tanggal_mulai, $this->tanggal_selesai])
            ->where('status', '!=', 'batal')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('livewire.laporan.laporan-kunjungan', [
            'laporanPoli' => $laporanPoli,
            'totalKunjungan' => $totalKunjungan,
            'rekapHarian' => $rekapHarian
        ])->layout('components.layouts.admin', ['title' => 'Laporan Kunjungan']);
    }
}