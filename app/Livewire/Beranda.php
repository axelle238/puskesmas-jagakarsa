<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Poli;
use App\Models\JadwalDokter;

class Beranda extends Component
{
    public function render()
    {
        // Ambil data Poli untuk ditampilkan di layanan
        $daftarPoli = Poli::withCount('tindakan')->get();

        // Ambil jadwal dokter yang aktif, diurutkan hari
        $jadwalDokter = JadwalDokter::with(['dokter', 'poli', 'dokter.pengguna'])
                        ->where('aktif', true)
                        ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                        ->orderBy('jam_mulai')
                        ->get()
                        ->groupBy('hari');

        return view('livewire.beranda', [
            'daftarPoli' => $daftarPoli,
            'jadwalDokter' => $jadwalDokter
        ])->layout('components.layouts.public', ['title' => 'Beranda - Puskesmas Jagakarsa']);
    }
}