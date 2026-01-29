<?php

namespace App\Livewire\Laporan;

use App\Models\RekamMedis;
use Livewire\Component;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Title('Laporan Kunjungan')]
class LaporanKunjungan extends Component
{
    public $tanggal_mulai;
    public $tanggal_selesai;

    public function mount()
    {
        $this->tanggal_mulai = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_selesai = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $kunjungans = RekamMedis::with(['pasien', 'poli', 'dokter.pengguna', 'tindakanDetail'])
            ->whereBetween('created_at', [
                Carbon::parse($this->tanggal_mulai)->startOfDay(),
                Carbon::parse($this->tanggal_selesai)->endOfDay()
            ])
            ->latest()
            ->get();

        // Hitung Ringkasan
        $totalPasien = $kunjungans->count();
        $totalPoli = $kunjungans->groupBy('poli.nama_poli')->map->count();
        
        // Hitung Estimasi Pendapatan (dari tindakan)
        $totalPendapatan = 0;
        foreach ($kunjungans as $k) {
            foreach ($k->tindakanDetail as $t) {
                $totalPendapatan += $t->pivot->biaya_saat_ini;
            }
        }

        return view('livewire.laporan.laporan-kunjungan', [
            'kunjungans' => $kunjungans,
            'totalPasien' => $totalPasien,
            'totalPoli' => $totalPoli,
            'totalPendapatan' => $totalPendapatan
        ])->layout('components.layouts.admin');
    }
}
