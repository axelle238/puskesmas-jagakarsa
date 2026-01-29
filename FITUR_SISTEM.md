# RANGKUMAN FITUR & FUNGSI SISTEM PUSKESMAS JAGAKARSA

Status Dokumen: **FINAL RELEASE**
Terakhir Diupdate: 29 January 2026

## 1. Ikhtisar Sistem
Sistem Informasi Puskesmas Jagakarsa (SIMPUS) adalah platform terintegrasi berbasis web yang dirancang untuk mendukung operasional Puskesmas modern dengan standar **Integrasi Layanan Primer (ILP)**. Sistem ini menghubungkan pelayanan front-office (pasien) dengan back-office (medis/admin) secara real-time.

## 2. Arsitektur & Teknologi
- **Framework:** Laravel 12 (PHP 8.2+)
- **Frontend:** Livewire 4 (SPA), Tailwind CSS, Alpine.js
- **Database:** MySQL (Relational)
- **Bahasa:** 100% Bahasa Indonesia

## 3. Modul Utama & Fitur Lengkap

### A. Halaman Publik (Front Office)
*Akses: Masyarakat Umum*
- **Beranda Interaktif:** Menampilkan informasi layanan, jadwal dokter, dan statistik.
- **Ambil Antrian Online:** Wizard pendaftaran antrian mandiri (Cek Data -> Pilih Poli -> Tiket).
- **Informasi Jadwal:** Jadwal praktik dokter real-time.

### B. Layanan Medis (Doctor's Desk)
*Akses: Dokter, Perawat*
- **Antrian Poli Real-time:** Dashboard khusus dokter untuk melihat pasien yang menunggu di polinya.
- **Pemeriksaan Medis (SOAP):**
  - **Subjektif:** Input keluhan utama & riwayat penyakit.
  - **Objektif:** Input tanda vital (Tensi, Suhu, Nadi, RR, BB, TB).
  - **Asesmen:** Input Diagnosis (ICD-10) dan diagnosis klinis.
  - **Plan:** Perencanaan terapi dan edukasi.
- **Resep Elektronik:** Input resep obat dinamis yang terintegrasi langsung dengan stok farmasi.
- **Tindakan Medis:** Input tindakan/jasa medis yang dilakukan.

### C. Farmasi (Apotek)
*Akses: Apoteker*
- **Antrian Resep Digital:** Menerima resep langsung dari meja dokter.
- **Workflow Status:** Kelola status obat (Menunggu -> Disiapkan -> Selesai).
- **Manajemen Stok:** CRUD data obat, pantau stok minimum & kedaluwarsa.

### D. Manajemen Admin (Back Office)
*Akses: Admin, Kepala Puskesmas*
- **Dasbor Operasional:** Statistik real-time.
- **Manajemen SDM:** Pendaftaran pegawai & akun login.
- **Manajemen Master:** Pengaturan Poli & Jadwal Praktik.
- **Laporan Kunjungan:** Rekapitulasi pasien dan estimasi pendapatan jasa medis.

## 4. Keamanan & Performa
- **Transaction Handling:** Penyimpanan data kritis menggunakan Database Transaction.
- **Role-Based Access Control:** Validasi akses ketat per modul.
- **Real-Time UI:** Livewire untuk interaksi tanpa reload.