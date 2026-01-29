# DOKUMENTASI FINAL: SISTEM INFORMASI PUSKESMAS JAGAKARSA

Sistem Informasi Manajemen Puskesmas (SIMPUS) berbasis Laravel 12 yang terintegrasi penuh, mencakup seluruh siklus operasional dari pendaftaran hingga pelaporan.

## 1. Identitas Sistem
- **Versi:** 1.5.0-Gold (Production Ready)
- **Status:** Stabil, Teruji, & Lengkap
- **Framework:** Laravel 12 / Livewire 3
- **Basis Data:** MySQL / MariaDB
- **Desain:** Tailwind CSS v4 (Modern & Responsif)

## 2. Fitur Utama & Modul

### A. Front-Office (Pelayanan Depan)
- **Portal Publik Dinamis:** CMS untuk banner, sambutan, artikel, dan fasilitas.
- **Pendaftaran Online:** Validasi NIK, deteksi pasien lama/baru otomatis.
- **Digital Signage (TV):** Layar antrian real-time untuk ruang tunggu.
- **Jadwal Dokter:** Terintegrasi langsung dengan database pegawai.

### B. Middle-Office (Pelayanan Medis)
- **Rekam Medis Elektronik (EMR):** Input SOAP standar medis.
- **E-Resep:** Prescribing digital terhubung ke stok farmasi.
- **Diagnosa ICD-10:** Input kode diagnosa untuk pelaporan.
- **Surat Keterangan:** Generator otomatis surat sakit/sehat.

### C. Back-Office (Penunjang & Manajemen)
- **Farmasi:** Manajemen stok obat cerdas (potong stok otomatis).
- **Laboratorium:** Input hasil pemeriksaan lab.
- **Kasir Terintegrasi:** Tagihan otomatis (Jasa + Obat + Lab).
- **Manajemen SDM:** Data pegawai & hak akses (Role-Based).
- **Audit Trail:** Log aktivitas keamanan sistem.

### D. Pelaporan (Executive Information System)
- **Dashboard Eksekutif:** Grafik tren kunjungan & pendapatan harian.
- **Laporan Morbiditas (LB1):** Statistik 10 besar penyakit.
- **Laporan Kunjungan:** Rekapitulasi pasien per poli/klaster.

## 3. Keamanan & Arsitektur
- **Role-Based Access Control (RBAC):** Middleware `CekPeran` membatasi akses (Admin, Dokter, Kasir, dll).
- **Validasi Ketat:** Lock status rekam medis yang sudah selesai.
- **Single Page Application (SPA):** Navigasi cepat tanpa reload menggunakan `wire:navigate`.

## 4. Panduan Singkat
1.  **Login Admin:** `admin@puskesmas.id` / `admin123`
2.  **Login Dokter:** `dokter@puskesmas.id` / `dokter123`
3.  **Setup Awal:** Isi data di menu **Master Data** (Poli, Tindakan, Jadwal).
4.  **Ubah Tampilan:** Gunakan menu **Pengaturan Instansi** untuk mengubah teks/gambar homepage.

---
*Dikembangkan dengan standar kualitas tinggi untuk mendukung transformasi digital kesehatan Indonesia.*
