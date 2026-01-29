# RANGKUMAN FITUR & FUNGSI SISTEM PUSKESMAS JAGAKARSA

Status Dokumen: **TERGENERASI OTOMATIS**
Terakhir Diupdate: 30 January 2026 01:46:47

## 1. Statistik Sistem
- **Total Model Database:** 27
- **Total Komponen Livewire:** 2
- **Total Migrasi Database:** 20

## 2. Struktur Database (Tabel)
- Buat Tabel Pengguna (`2024_01_01_000001_buat_tabel_pengguna`)
- Buat Tabel Poli (`2024_01_01_000002_buat_tabel_poli`)
- Buat Tabel Pasien (`2024_01_01_000003_buat_tabel_pasien`)
- Buat Tabel Pegawai (`2024_01_01_000004_buat_tabel_pegawai`)
- Buat Tabel Jadwal (`2024_01_01_000005_buat_tabel_jadwal`)
- Buat Tabel Antrian (`2024_01_01_000006_buat_tabel_antrian`)
- Buat Tabel Rekam Medis (`2024_01_01_000007_buat_tabel_rekam_medis`)
- Buat Tabel Penunjang Medis (`2024_01_01_000008_buat_tabel_penunjang_medis`)
- Tambah Klaster Ke Poli (`2024_01_01_000009_tambah_klaster_ke_poli`)
- Buat Tabel Cms Publik (`2024_01_01_000010_buat_tabel_cms_publik`)
- Buat Tabel Administrasi Medis (`2024_01_01_000011_buat_tabel_administrasi_medis`)
- Buat Tabel Lab Dan Kasir (`2024_01_01_000012_buat_tabel_lab_dan_kasir`)
- Buat Tabel Profil Instansi (`2024_01_01_000013_buat_tabel_profil_instansi`)
- Buat Tabel Detail Resep (`2024_01_01_000014_buat_tabel_detail_resep`)
- Buat Tabel Surat (`2024_01_01_000015_buat_tabel_surat`)
- Buat Tabel Log (`2024_01_01_000016_buat_tabel_log`)
- Buat Tabel Log Fix (`2026_01_29_000001_buat_tabel_log_fix`)
- Update Profil Instansi Untuk Cms (`2026_01_30_000001_update_profil_instansi_untuk_cms`)
- Buat Tabel Manajemen Barang (`2026_01_30_000002_buat_tabel_manajemen_barang`)
- Buat Tabel Manajemen It Security (`2026_01_30_000003_buat_tabel_manajemen_it_security`)

## 3. Peta Rute & Halaman
- **POST** `/_boost/browser-logs` -> `Fungsi Langsung`
- **GET|HEAD** `/livewire-7a744fa5/livewire.js` -> `Livewire\Mechanisms\FrontendAssets\FrontendAssets@returnJavaScriptAsFile`
- **GET|HEAD** `/livewire-7a744fa5/livewire.min.js.map` -> `Livewire\Mechanisms\FrontendAssets\FrontendAssets@maps`
- **GET|HEAD** `/livewire-7a744fa5/livewire.csp.min.js.map` -> `Livewire\Mechanisms\FrontendAssets\FrontendAssets@cspMaps`
- **POST** `/livewire-7a744fa5/upload-file` -> `Livewire\Features\SupportFileUploads\FileUploadController@handle`
- **GET|HEAD** `/livewire-7a744fa5/preview-file/{filename}` -> `Livewire\Features\SupportFileUploads\FilePreviewController@handle`
- **GET|HEAD** `/livewire-7a744fa5/js/{component}.js` -> `Fungsi Langsung`
- **GET|HEAD** `/livewire-7a744fa5/css/{component}.css` -> `Fungsi Langsung`
- **GET|HEAD** `/livewire-7a744fa5/css/{component}.global.css` -> `Fungsi Langsung`
- **GET|HEAD** `/up` -> `Fungsi Langsung`
- **GET|HEAD** `//` -> `App\Livewire\Publik\Beranda`
- **GET|HEAD** `/masuk` -> `App\Livewire\Auth\Masuk`
- **GET|HEAD** `/login` -> `Fungsi Langsung`
- **GET|HEAD** `/antrian-online` -> `App\Livewire\Publik\AmbilAntrian`
- **GET|HEAD** `/layar-antrian` -> `App\Livewire\Publik\LayarAntrian`
- **GET|HEAD** `/artikel` -> `App\Livewire\Publik\EdukasiKesehatan`
- **GET|HEAD** `/artikel/{slug}` -> `App\Livewire\Publik\BacaArtikel`
- **GET|HEAD** `/fasilitas` -> `App\Livewire\Publik\FasilitasPublik`
- **GET|HEAD** `/layanan` -> `Fungsi Langsung`
- **GET|HEAD** `/dasbor` -> `App\Livewire\Dasbor`
- **GET|HEAD** `/profil` -> `App\Livewire\Pengaturan\Profil`
- **GET|HEAD** `/pasien` -> `App\Livewire\Pasien\DaftarPasien`
- **GET|HEAD** `/medis/antrian` -> `App\Livewire\Medis\AntrianPoli`
- **GET|HEAD** `/medis/periksa/{idAntrian}` -> `App\Livewire\Medis\Pemeriksaan`
- **GET|HEAD** `/medis/surat` -> `App\Livewire\Medis\BuatSurat`
- **GET|HEAD** `/laboratorium` -> `App\Livewire\Laboratorium\InputHasil`
- **GET|HEAD** `/farmasi/resep` -> `App\Livewire\Farmasi\DaftarResep`
- **GET|HEAD** `/farmasi/stok` -> `App\Livewire\Farmasi\StokObat`
- **GET|HEAD** `/kasir` -> `App\Livewire\Kasir\Pembayaran`
- **GET|HEAD** `/pegawai` -> `App\Livewire\Pegawai\DaftarPegawai`
- **GET|HEAD** `/barang` -> `App\Livewire\Barang\DaftarBarang`
- **GET|HEAD** `/master/poli` -> `App\Livewire\Master\DaftarPoli`
- **GET|HEAD** `/master/tindakan` -> `App\Livewire\Master\DaftarTindakan`
- **GET|HEAD** `/master/jadwal` -> `App\Livewire\Master\JadwalPraktik`
- **GET|HEAD** `/pengaturan/instansi` -> `App\Livewire\Pengaturan\ProfilInstansiController`
- **GET|HEAD** `/pengaturan/keamanan` -> `App\Livewire\Pengaturan\ManajemenIT`
- **GET|HEAD** `/pengaturan/log` -> `App\Livewire\Pengaturan\LogAktivitas`
- **GET|HEAD** `/publikasi/artikel` -> `App\Livewire\Publikasi\KelolaArtikel`
- **GET|HEAD** `/publikasi/fasilitas` -> `App\Livewire\Publikasi\KelolaFasilitas`
- **GET|HEAD** `/laporan/kunjungan` -> `App\Livewire\Laporan\LaporanKunjungan`
- **GET|HEAD** `/laporan/penyakit` -> `App\Livewire\Laporan\LaporanPenyakit`
- **POST** `/keluar` -> `Fungsi Langsung`
- **GET|HEAD** `/storage/{path}` -> `Fungsi Langsung`
- **POST** `/livewire-7a744fa5/update` -> `Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate`

## 4. Modul Utama (Livewire)
- **Beranda**: Komponen interaktif SPA.
- **Dasbor**: Komponen interaktif SPA.
