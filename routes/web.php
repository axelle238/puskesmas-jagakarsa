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
use App\Livewire\Medis\BuatSurat;
use App\Livewire\Laboratorium\InputHasil;
use App\Livewire\Kasir\Pembayaran;
use App\Livewire\Farmasi\DaftarResep;
use App\Livewire\Farmasi\StokObat;
use App\Livewire\Laporan\LaporanKunjungan;
use App\Livewire\Laporan\LaporanPenyakit;
use App\Livewire\Pengaturan\Profil;
use App\Livewire\Pengaturan\LogAktivitas;
use App\Livewire\Publik\AmbilAntrian;
use App\Livewire\Publik\EdukasiKesehatan;
use App\Livewire\Publik\BacaArtikel;
use App\Livewire\Publik\FasilitasPublik;
use App\Livewire\Publik\LayarAntrian;
use App\Livewire\Publikasi\KelolaArtikel;
use App\Livewire\Publikasi\KelolaFasilitas;

// -----------------------------------------------------------------------------
// HALAMAN PUBLIK (Akses Terbuka)
// -----------------------------------------------------------------------------
Route::get('/', Beranda::class)->name('beranda');
Route::get('/masuk', Masuk::class)->name('login'); // Name 'login' tetap standar Laravel auth

// Layanan Publik
Route::get('/ambil-antrian', AmbilAntrian::class)->name('publik.ambil-antrian');
Route::get('/layar-antrian', LayarAntrian::class)->name('publik.layar-antrian');
Route::get('/jadwal-dokter', JadwalPraktik::class)->name('publik.jadwal'); // Reuse komponen dulu dengan mode view-only

// Informasi & Edukasi
Route::get('/artikel', EdukasiKesehatan::class)->name('publik.artikel');
Route::get('/artikel/{slug}', BacaArtikel::class)->name('publik.artikel.baca');
Route::get('/fasilitas', FasilitasPublik::class)->name('publik.fasilitas');
Route::get('/layanan', function() { return redirect('/'); }); // Redirect sementara ke beranda

// -----------------------------------------------------------------------------
// HALAMAN SISTEM (Butuh Login)
// -----------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/dasbor', Dasbor::class)->name('dasbor');
    
    // 1. MANAJEMEN PENDAFTARAN & PASIEN
    Route::prefix('pasien')->group(function () {
        Route::get('/', DaftarPasien::class)->name('pasien.daftar');
    });

    // 2. MANAJEMEN MEDIS (DOKTER/PERAWAT)
    Route::prefix('medis')->group(function () {
        Route::get('/antrian', AntrianPoli::class)->name('medis.antrian');
        Route::get('/periksa/{idAntrian}', Pemeriksaan::class)->name('medis.periksa');
        Route::get('/surat', BuatSurat::class)->name('medis.surat');
    });

    // 3. PENUNJANG (LAB & FARMASI)
    Route::get('/laboratorium', InputHasil::class)->name('lab.input');
    
    Route::prefix('farmasi')->group(function () {
        Route::get('/resep', DaftarResep::class)->name('farmasi.resep');
        Route::get('/stok', StokObat::class)->name('farmasi.stok');
    });

    // 4. KEUANGAN
    Route::get('/kasir', Pembayaran::class)->name('kasir.bayar');

    // 5. MANAJEMEN SDM (PEGAWAI)
    Route::get('/pegawai', DaftarPegawai::class)->name('pegawai.daftar');

    // 6. MASTER DATA
    Route::prefix('master')->group(function () {
        Route::get('/poli', DaftarPoli::class)->name('master.poli');
        Route::get('/jadwal', JadwalPraktik::class)->name('master.jadwal');
    });

    // 7. PUBLIKASI (CMS)
    Route::prefix('publikasi')->group(function () {
        Route::get('/artikel', KelolaArtikel::class)->name('cms.artikel');
        Route::get('/fasilitas', KelolaFasilitas::class)->name('cms.fasilitas');
    });

    // 8. LAPORAN
    Route::prefix('laporan')->group(function () {
        Route::get('/kunjungan', LaporanKunjungan::class)->name('laporan.kunjungan');
        Route::get('/penyakit', LaporanPenyakit::class)->name('laporan.penyakit');
    });

    // 9. PENGATURAN
    Route::get('/profil', Profil::class)->name('pengaturan.profil');
    Route::get('/log-aktivitas', LogAktivitas::class)->name('pengaturan.log');
    
    // Logout route (biasanya via POST/Livewire action, tapi ini fallback)
    Route::post('/keluar', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});