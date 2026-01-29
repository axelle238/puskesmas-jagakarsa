# DOKUMENTASI HIDUP: SISTEM INFORMASI PUSKESMAS JAGAKARSA

Dokumen ini diperbarui secara otomatis seiring perkembangan sistem.

## 1. Status Sistem
- **Versi:** 1.2.0-beta
- **Framework:** Laravel 12 / Livewire 3
- **Basis Data:** MySQL / MariaDB
- **Bahasa Kode:** 100% Bahasa Indonesia

## 2. Struktur Modul & Progres

### A. Manajemen Pengguna & Autentikasi
- [x] Tabel `pengguna` (Migrasi)
- [x] Model `App\Models\Pengguna` (Autentikasi Kustom)
- [x] Halaman Login (`Livewire\Auth\Masuk`)
- [x] Middleware Peran (Admin, Dokter, dll)

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
- [ ] Input Hasil Lab

### E. Keuangan
- [x] Tabel `tagihan`
- [ ] Kasir & Pembayaran

## 3. Fitur Unggulan Terselesaikan
1.  **Siklus Medis Terintegrasi:**
    - Dokter input SOAP -> Resep otomatis masuk ke Farmasi -> Stok terpotong saat Apoteker proses.
    - Riwayat medis pasien tersimpan dan mudah diakses dokter saat pemeriksaan.

2.  **Antrian Online & Poli:**
    - Pasien daftar online -> Masuk antrian poli -> Status berubah real-time (Menunggu -> Dipanggil -> Diperiksa -> Selesai).

3.  **Manajemen Stok Cerdas:**
    - Indikator stok menipis dan tanggal kedaluwarsa.
    - Validasi stok saat dokter meresepkan obat.

## 4. Konvensi Penamaan (Wajib)
- **Model:** Singular, PascalCase (contoh: `Pasien`, `RekamMedis`)
- **Tabel:** Plural/Singular, snake_case (contoh: `pasien`, `rekam_medis`)
- **Controller/Livewire:** Deskriptif (contoh: `DaftarPasien`, `InputHasilLab`)
- **Route:** Kebab-case, Bahasa Indonesia (contoh: `/pendaftaran/antrian-baru`)