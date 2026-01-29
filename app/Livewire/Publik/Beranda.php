<?php

namespace App\Livewire\Publik;

use App\Models\ArtikelEdukasi;
use App\Models\Fasilitas;
use App\Models\JadwalDokter;
use App\Models\Poli;
use App\Models\ProfilInstansi;
use Livewire\Component;

class Beranda extends Component
{
    public function render()
    {
        // 1. Profil & Konten Statis
        $profil = ProfilInstansi::first();
        
        if (!$profil) {
            // Fallback object jika database kosong
            $profil = new ProfilInstansi([
                'nama_instansi' => 'Puskesmas Jagakarsa',
                'alamat' => 'Jl. Moh. Kahfi 1, Jagakarsa, Jakarta Selatan',
                'telepon' => '(021) 786-xxxx',
                'hero_title' => 'Melayani dengan Hati, Menuju Jagakarsa Sehat',
                'hero_subtitle' => 'Pelayanan kesehatan primer yang terintegrasi, modern, dan dapat diandalkan oleh seluruh keluarga.',
                'sambutan_kepala' => 'Selamat datang di website resmi Puskesmas Jagakarsa. Kami berkomitmen untuk terus berinovasi dalam memberikan pelayanan kesehatan terbaik.',
                'nama_kepala_puskesmas' => 'Kepala Puskesmas',
                'foto_kepala' => null,
                'hero_image' => null
            ]);
        }

        // 2. Layanan / Poli (Aktif saja)
        $layanan = Poli::where('aktif', true)->take(6)->get();

        // 3. Artikel Terbaru (3 biji)
        $artikel = ArtikelEdukasi::where('publikasi', true)
            ->latest()
            ->take(3)
            ->get();

        // 4. Fasilitas Unggulan
        $fasilitas = Fasilitas::take(4)->get();

        // 5. Jadwal Dokter Hari Ini
        $jadwalHariIni = JadwalDokter::with(['dokter.pengguna', 'poli'])
            ->where('hari', date('l')) // Hari dalam bahasa Inggris (Monday, Tuesday...)
            ->where('aktif', true)
            ->take(6)
            ->get();

        return view('livewire.publik.beranda', [
            'profil' => $profil,
            'layanan' => $layanan,
            'artikel' => $artikel,
            'fasilitas' => $fasilitas,
            'jadwal' => $jadwalHariIni
        ])->layout('components.layouts.publik');
    }
}
