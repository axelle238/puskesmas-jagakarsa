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
use App\Livewire\Publik\AmbilAntrian;

// Halaman Publik
Route::get('/', Beranda::class)->name('beranda');
Route::get('/login', Masuk::class)->name('login');
Route::get('/antrian', AmbilAntrian::class)->name('antrian.ambil');

// Halaman Admin / Pegawai (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dasbor', Dasbor::class)->name('dasbor');
    
    // Manajemen Utama
    Route::get('/pasien', DaftarPasien::class)->name('pasien.daftar');
    Route::get('/pegawai', DaftarPegawai::class)->name('pegawai.daftar');
    
    // Master Data
    Route::get('/poli', DaftarPoli::class)->name('master.poli');
    Route::get('/jadwal', JadwalPraktik::class)->name('master.jadwal');
    
    // Layanan Medis (Dokter/Perawat)
    Route::get('/pemeriksaan', AntrianPoli::class)->name('medis.antrian');
    Route::get('/pemeriksaan/{idAntrian}', Pemeriksaan::class)->name('medis.periksa');

    // Farmasi (Apoteker)
    Route::get('/farmasi/resep', DaftarResep::class)->name('farmasi.resep');
    Route::get('/farmasi/stok', StokObat::class)->name('farmasi.stok');

    // Laporan
    Route::get('/laporan/kunjungan', LaporanKunjungan::class)->name('laporan.kunjungan');
});
