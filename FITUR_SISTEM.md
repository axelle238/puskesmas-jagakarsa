# DOKUMENTASI HIDUP: SISTEM INFORMASI PUSKESMAS JAGAKARSA

Dokumen ini diperbarui secara otomatis seiring perkembangan sistem.

## 1. Status Sistem
- **Versi:** 1.4.0-Stable
- **Framework:** Laravel 12 / Livewire 3
- **Basis Data:** MySQL / MariaDB
- **Bahasa Kode:** 100% Bahasa Indonesia

## 2. Struktur Modul & Progres

### A. Manajemen Pengguna & Autentikasi
- [x] Tabel `pengguna` (Migrasi)
- [x] Model `App\Models\Pengguna` (Autentikasi Kustom)
- [x] Halaman Login (`Livewire\Auth\Masuk`)
- [x] Middleware Peran (Admin, Dokter, dll)
- [x] **Pengaturan Profil & Keamanan**

### B. Manajemen Master Data
- [x] Tabel `poli` (Klaster ILP)
- [x] Tabel `pegawai` (Data SDM)
- [x] Tabel `jadwal_praktik`
- [x] CRUD Data Pegawai (Auto-create User)
- [x] CRUD Master Poli
- [x] CRUD Jadwal Praktik Dokter

### C. Layanan Medis (ILP)
- [x] Tabel `pasien`
- [x] Tabel `antrian`
- [x] Tabel `rekam_medis`
- [x] Modul Pendaftaran Pasien (CRUD)
- [x] Modul Antrian Online (Frontend Publik)
- [x] Modul Antrian Poli (Dokter/Perawat View - Realtime)
- [x] **Pemeriksaan Dokter (SOAP & Diagnosa)**
- [x] **Electronic Prescribing (E-Resep)**

### D. Penunjang Medis (Farmasi & Lab)
- [x] Tabel `obat` (Farmasi)
- [x] Tabel `detail_resep`
- [x] Tabel `hasil_lab` (Laboratorium)
- [x] **Manajemen Stok Obat (CRUD & Monitoring)**
- [x] **Proses Resep Apoteker (Potong Stok Otomatis)**
- [x] **Permintaan & Input Hasil Laboratorium**

### E. Keuangan & Laporan
- [x] Tabel `tagihan` & `detail_tagihan`
- [x] **Kasir & Billing System (Integrasi Poli + Obat + Lab)**
- [x] **Laporan Kunjungan Pasien**

### F. Manajemen Konten Publik (CMS)
- [x] Tabel `artikel_edukasi` & `fasilitas`
- [x] **CMS Artikel Kesehatan (CRUD + Upload)**
- [x] **CMS Fasilitas (CRUD + Upload)**
- [x] **Halaman Publik: Edukasi Kesehatan**
- [x] **Halaman Publik: Fasilitas**

## 3. Fitur Unggulan Terselesaikan
1.  **Siklus Medis End-to-End:**
    - Alur: Pendaftaran -> Antrian Poli -> Pemeriksaan Dokter -> Resep/Lab -> Kasir -> Selesai.
    - Semua modul terintegrasi dalam satu database.

2.  **Keuangan Terpusat:**
    - Tagihan otomatis menarik data biaya dari tindakan dokter, obat farmasi, dan pemeriksaan lab.
    - Mendukung metode pembayaran Tunai, QRIS, dan BPJS (Gratis).

3.  **Portal Informasi Publik:**
    - Masyarakat dapat membaca artikel kesehatan dan melihat fasilitas tanpa login.
    - Konten dikelola langsung oleh petugas via Admin Panel.

## 4. Konvensi Penamaan (Wajib)
- **Model:** Singular, PascalCase (contoh: `Pasien`, `RekamMedis`)
- **Tabel:** Plural/Singular, snake_case (contoh: `pasien`, `rekam_medis`)
- **Controller/Livewire:** Deskriptif (contoh: `DaftarPasien`, `InputHasilLab`)
- **Route:** Kebab-case, Bahasa Indonesia (contoh: `/pendaftaran/antrian-baru`)