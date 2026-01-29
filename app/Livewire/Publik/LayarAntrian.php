<?php

namespace App\Livewire\Publik;

use App\Models\Antrian;
use App\Models\Poli;
use Carbon\Carbon;
use Livewire\Component;

class LayarAntrian extends Component
{
    // Polling setiap 5 detik agar tampilan selalu update
    public function render()
    {
        $hariIni = Carbon::today();

        // 1. Antrian yang sedang dipanggil (Status 'dipanggil')
        // Ambil yang terakhir diupdate (paling baru dipanggil)
        $panggilanTerakhir = Antrian::with(['poli', 'jadwal.dokter.pengguna'])
            ->whereDate('tanggal_antrian', $hariIni)
            ->where('status', 'dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();

        // 2. Rekap Antrian per Poli
        $rekapPoli = Poli::where('aktif', true)
            ->with(['antrian' => function($q) use ($hariIni) {
                $q->whereDate('tanggal_antrian', $hariIni)
                  ->whereIn('status', ['menunggu', 'dipanggil', 'diperiksa']);
            }])
            ->get()
            ->map(function($poli) {
                // Cari nomor yang sedang aktif di poli ini
                $sedangDipanggil = $poli->antrian->where('status', 'dipanggil')->first();
                $antrianSelanjutnya = $poli->antrian->where('status', 'menunggu')->first();
                
                return [
                    'nama_poli' => $poli->nama_poli,
                    'kode_poli' => $poli->kode_poli,
                    'nomor_dipanggil' => $sedangDipanggil ? $sedangDipanggil->nomor_antrian : '-',
                    'sisa_antrian' => $poli->antrian->where('status', 'menunggu')->count(),
                    'dokter' => $sedangDipanggil ? ($sedangDipanggil->jadwal->dokter->pengguna->nama_lengkap ?? 'Dokter') : '-'
                ];
            });

        return view('livewire.publik.layar-antrian', [
            'panggilanTerakhir' => $panggilanTerakhir,
            'rekapPoli' => $rekapPoli
        ])->layout('components.layouts.public', ['title' => 'Layar Antrian']); 
        // Menggunakan layout public kosong/khusus full screen biasanya
    }
}