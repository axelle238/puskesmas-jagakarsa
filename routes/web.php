<?php

use Illuminate\Support\Facades\Route;

// Import Livewire Components
use App\Livewire\Publik\Beranda;
use App\Livewire\Auth\Masuk;

// Publik Components
use App\Livewire\Publik\AmbilAntrian;
use App\Livewire\Publik\LayarAntrian;
use App\Livewire\Publik\EdukasiKesehatan;
use App\Livewire\Publik\BacaArtikel;
use App\Livewire\Publik\FasilitasPublik;

// Internal Components
use App\Livewire\Dasbor;
use App\Livewire\Pasien\DaftarPasien;
use App\Livewire\Pegawai\DaftarPegawai;
use App\Livewire\Master\DaftarPoli;
use App\Livewire\Master\DaftarTindakan;
use App\Livewire\Master\JadwalPraktik;
use App\Livewire\Medis\AntrianPoli;
use App\Livewire\Medis\Pemeriksaan;
use App\Livewire\Medis\BuatSurat;
use App\Livewire\Laboratorium\InputHasil;
use App\Livewire\Kasir\Pembayaran;
use App\Livewire\Farmasi\DaftarResep;
use App\Livewire\Farmasi\StokObat;
use App\Livewire\Barang\DaftarBarang;
use App\Livewire\Laporan\LaporanKunjungan;
use App\Livewire\Laporan\LaporanPenyakit;
use App\Livewire\Pengaturan\Profil;
use App\Livewire\Pengaturan\LogAktivitas;
use App\Livewire\Pengaturan\ProfilInstansiController;
use App\Livewire\Pengaturan\ManajemenIT;
use App\Livewire\Publikasi\KelolaArtikel;
use App\Livewire\Publikasi\KelolaFasilitas;
use App\Livewire\Perencanaan\DaftarKegiatan; // Import Perencanaan
use App\Livewire\Kesekretariatan\SuratMasuk;

// -----------------------------------------------------------------------------
// HALAMAN PUBLIK (Akses Terbuka)
// -----------------------------------------------------------------------------

// Beranda & Login
Route::get('/', Beranda::class)->name('beranda');
Route::get('/masuk', Masuk::class)->name('login');
Route::get('/login', function() { return redirect('/masuk'); }); // Alias

// Antrian Publik
Route::get('/antrian-online', AmbilAntrian::class)->name('publik.antrian'); // Ganti URL lebih user friendly
Route::get('/layar-antrian', LayarAntrian::class)->name('publik.layar-antrian');

// Informasi Publik
Route::get('/artikel', EdukasiKesehatan::class)->name('publik.artikel');
Route::get('/artikel/{slug}', BacaArtikel::class)->name('publik.artikel.baca');
Route::get('/fasilitas', FasilitasPublik::class)->name('publik.fasilitas');
Route::get('/layanan', function() { return redirect('/#layanan'); }); // Redirect ke section di homepage

// -----------------------------------------------------------------------------
// HALAMAN SISTEM (Butuh Login)
// -----------------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {
    
    // Dashboard (Semua User Login)
    Route::get('/dasbor', Dasbor::class)->name('dasbor');
    
    // Profil User
    Route::get('/profil', Profil::class)->name('pengaturan.profil');

    // -------------------------------------------------------------------------
    // ROLE: ADMIN & PENDAFTARAN
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,pendaftaran')->group(function() {
        Route::get('/pasien', DaftarPasien::class)->name('pasien.daftar');
    });

    // -------------------------------------------------------------------------
    // ROLE: MEDIS (Dokter, Perawat)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,dokter,perawat')->prefix('medis')->group(function () {
        Route::get('/antrian', AntrianPoli::class)->name('medis.antrian');
        Route::get('/periksa/{idAntrian}', Pemeriksaan::class)->name('medis.periksa');
        Route::get('/surat', BuatSurat::class)->name('medis.surat');
    });

    // -------------------------------------------------------------------------
    // ROLE: PENUNJANG (Lab, Farmasi)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,analis')->get('/laboratorium', InputHasil::class)->name('lab.input');

    Route::middleware('peran:admin,apoteker')->prefix('farmasi')->group(function () {
        Route::get('/resep', DaftarResep::class)->name('farmasi.resep');
        Route::get('/stok', StokObat::class)->name('farmasi.stok');
    });

    // -------------------------------------------------------------------------
    // ROLE: KEUANGAN (Kasir)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,kasir')->get('/kasir', Pembayaran::class)->name('kasir.bayar');

    // -------------------------------------------------------------------------
    // ROLE: ADMIN (Manajemen Pusat)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin')->group(function () {
        // SDM
        Route::get('/pegawai', DaftarPegawai::class)->name('pegawai.daftar');
        
        // Aset & Barang
        Route::get('/barang', DaftarBarang::class)->name('barang.daftar');

        // Master Data
        Route::prefix('master')->group(function () {
            Route::get('/poli', DaftarPoli::class)->name('master.poli');
            Route::get('/tindakan', DaftarTindakan::class)->name('master.tindakan');
            Route::get('/jadwal', JadwalPraktik::class)->name('master.jadwal');
        });

        // Pengaturan Sistem
        Route::prefix('pengaturan')->group(function () {
            Route::get('/instansi', ProfilInstansiController::class)->name('pengaturan.instansi');
            Route::get('/keamanan', ManajemenIT::class)->name('pengaturan.keamanan');
            Route::get('/log', LogAktivitas::class)->name('pengaturan.log');
        });
    });

    // -------------------------------------------------------------------------
    // CMS PUBLIKASI (Admin & Dokter)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,dokter')->prefix('publikasi')->group(function () {
        Route::get('/artikel', KelolaArtikel::class)->name('cms.artikel');
        Route::get('/fasilitas', KelolaFasilitas::class)->name('cms.fasilitas');
    });

    // -------------------------------------------------------------------------
    // MANAJEMEN PERENCANAAN (Admin & Kepala Puskesmas)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,kapus')->prefix('perencanaan')->group(function () {
        Route::get('/kegiatan', DaftarKegiatan::class)->name('perencanaan.kegiatan');
    });

    // -------------------------------------------------------------------------
    // MANAJEMEN KESEKRETARIATAN (TU)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,kapus')->prefix('surat')->group(function () {
        Route::get('/masuk', SuratMasuk::class)->name('surat.masuk');
    });

    // -------------------------------------------------------------------------
    // LAPORAN (Admin & Kepala Puskesmas)
    // -------------------------------------------------------------------------
    Route::middleware('peran:admin,kapus')->prefix('laporan')->group(function () {
        Route::get('/kunjungan', LaporanKunjungan::class)->name('laporan.kunjungan');
        Route::get('/penyakit', LaporanPenyakit::class)->name('laporan.penyakit');
    });

    // Logout
    Route::post('/keluar', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
