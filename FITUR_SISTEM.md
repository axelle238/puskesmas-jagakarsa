# DOKUMENTASI HIDUP: SISTEM INFORMASI PUSKESMAS JAGAKARSA

Dokumen ini diperbarui secara otomatis seiring perkembangan sistem.

## 1. Status Sistem
- **Versi:** 1.0.0-beta
- **Framework:** Laravel 12 / Livewire 3
- **Basis Data:** MySQL / MariaDB
- **Bahasa Kode:** 100% Bahasa Indonesia

## 2. Struktur Modul & Progres

### A. Manajemen Pengguna & Autentikasi
- [x] Tabel `pengguna` (Migrasi)
- [x] Model `App\Models\Pengguna` (Autentikasi Kustom)
- [x] Halaman Login (`Livewire\Auth\Masuk`)
- [x] Middleware Peran (Admin, Dokter, dll) - *Menggunakan Auth default*

### B. Manajemen Master Data
- [x] Tabel `poli` (Klaster ILP)
- [x] Tabel `pegawai`
- [x] Tabel `jadwal_praktik`
- [ ] CRUD Data Poli
- [ ] CRUD Data Pegawai

### C. Layanan Medis (ILP)
- [x] Tabel `pasien`
- [x] Tabel `antrian`
- [x] Tabel `rekam_medis`
- [x] Modul Pendaftaran Pasien (CRUD)
- [x] Modul Antrian Online (Frontend Publik)
- [x] Dashboard Admin (Statistik Realtime)

### D. Penunjang Medis
- [x] Tabel `obat` (Farmasi)
- [x] Tabel `hasil_lab` (Laboratorium)
- [ ] Manajemen Stok Obat
- [ ] Input Hasil Lab

### E. Keuangan
- [x] Tabel `tagihan`
- [ ] Kasir & Pembayaran

## 3. Fitur Unggulan Terselesaikan
1.  **Antrian Online Terintegrasi ILP:**
    - Pasien dapat mendaftar mandiri via web.
    - Otomatisasi nomor antrian berdasarkan poli.
    - Deteksi pasien lama (NIK) vs pasien baru.

2.  **Dashboard Real-Time:**
    - Statistik kunjungan harian.
    - Monitor antrian aktif.
    - Peringatan dini stok obat menipis.

3.  **Manajemen Pasien Lengkap:**
    - CRUD Data Pasien dengan validasi NIK.
    - Riwayat kunjungan (persiapan).
    - Status BPJS vs Umum.

## 4. Konvensi Penamaan (Wajib)
- **Model:** Singular, PascalCase (contoh: `Pasien`, `RekamMedis`)
- **Tabel:** Plural/Singular, snake_case (contoh: `pasien`, `rekam_medis`)
- **Controller/Livewire:** Deskriptif (contoh: `DaftarPasien`, `InputHasilLab`)
- **Route:** Kebab-case, Bahasa Indonesia (contoh: `/pendaftaran/antrian-baru`)