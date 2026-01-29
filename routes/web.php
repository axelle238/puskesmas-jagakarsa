<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Beranda;
use App\Livewire\Dasbor;
use App\Livewire\Auth\Masuk;
use App\Livewire\Pasien\DaftarPasien;
use App\Livewire\Pegawai\DaftarPegawai;
use App\Livewire\Master\DaftarPoli;
use App\Livewire\Master\JadwalPraktik;
use App\Livewire\Medis\AntrianPoli;
use App\Livewire\Medis\Pemeriksaan;
use App\Livewire\Farmasi\DaftarResep;
use App\Livewire\Farmasi\StokObat;
use App\Livewire\Laporan\LaporanKunjungan;
use App\Livewire\Laporan\LaporanPenyakit;
use App\Livewire\Pengaturan\Profil; // Import Baru
use App\Livewire\Publik\AmbilAntrian;
use App\Livewire\Publik\EdukasiKesehatan;
use App\Livewire\Publik\BacaArtikel;
use App\Livewire\Publik\FasilitasPublik;
use App\Livewire\Publik\LayarAntrian;
use App\Livewire\Publikasi\KelolaArtikel;
use App\Livewire\Publikasi\KelolaFasilitas;

// Halaman Publik
Route::get('/', Beranda::class)->name('beranda');
Route::get('/login', Masuk::class)->name('login');
Route::get('/antrian', AmbilAntrian::class)->name('antrian.ambil');
Route::get('/layar-antrian', LayarAntrian::class)->name('layar.antrian');
Route::get('/edukasi', EdukasiKesehatan::class)->name('edukasi');
Route::get('/edukasi/{slug}', BacaArtikel::class)->name('artikel.baca');
Route::get('/fasilitas', FasilitasPublik::class)->name('fasilitas.publik');

// Halaman Admin / Pegawai (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dasbor', Dasbor::class)->name('dasbor');
    
    // Manajemen Utama
    Route::get('/pasien', DaftarPasien::class)->name('pasien.daftar');
    Route::get('/pegawai', DaftarPegawai::class)->name('pegawai.daftar');
    
    // Master Data
    Route::get('/poli', DaftarPoli::class)->name('master.poli');
    Route::get('/jadwal', JadwalPraktik::class)->name('master.jadwal');
    
    // Publikasi (CMS)
    Route::get('/publikasi/artikel', KelolaArtikel::class)->name('cms.artikel');
    Route::get('/publikasi/fasilitas', KelolaFasilitas::class)->name('cms.fasilitas');
    
    // Layanan Medis (Dokter/Perawat)
    Route::get('/pemeriksaan', AntrianPoli::class)->name('medis.antrian');
    Route::get('/pemeriksaan/{idAntrian}', Pemeriksaan::class)->name('medis.periksa');

    // Farmasi (Apoteker)
    Route::get('/farmasi/resep', DaftarResep::class)->name('farmasi.resep');
    Route::get('/farmasi/stok', StokObat::class)->name('farmasi.stok');

    // Laporan
    Route::get('/laporan/kunjungan', LaporanKunjungan::class)->name('laporan.kunjungan');
    Route::get('/laporan/penyakit', LaporanPenyakit::class)->name('laporan.penyakit');

    // Pengaturan
    Route::get('/profil', Profil::class)->name('pengaturan.profil'); // Route Baru
});
