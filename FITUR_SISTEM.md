# RANGKUMAN FITUR & FUNGSI SISTEM PUSKESMAS JAGAKARSA

Status Dokumen: **FINAL RELEASE v1.4 (COMPLETE SUITE)**
Terakhir Diupdate: 29 January 2026

## 1. Ikhtisar Sistem
Sistem Informasi Puskesmas Jagakarsa (SIMPUS) adalah platform terintegrasi berbasis web yang dirancang untuk mendukung operasional Puskesmas modern dengan standar **Integrasi Layanan Primer (ILP)**. Sistem ini menghubungkan pelayanan front-office (pasien) dengan back-office (medis/admin) secara real-time.

## 2. Fitur Unggulan (Advanced)
- **Layar Antrian TV (Digital Signage):** Tampilan full-screen untuk ruang tunggu dengan pemanggilan suara otomatis (Text-to-Speech).
- **Visual Analytics Dashboard:** Grafik tren kunjungan mingguan real-time.
- **Epidemiology Report:** Analisis otomatis 10 Besar Penyakit (Morbiditas) berdasarkan kode ICD-10.
- **Smart Printing:** Cetak Tiket Antrian (Thermal) dan Copy Resep Dokter otomatis.
- **CMS Publik:** Manajemen konten edukasi kesehatan dan fasilitas.

## 3. Modul Utama

### A. Halaman Publik (Front Office)
*Akses: Masyarakat Umum*
- **Layar Antrian TV:** Menampilkan nomor yang dipanggil dan status per poli secara real-time.
- **Beranda Interaktif:** Menampilkan informasi layanan, jadwal dokter, dan statistik.
- **CMS Edukasi Kesehatan:** Portal artikel/berita kesehatan yang dikelola admin/dokter.
- **Info Fasilitas:** Direktori fasilitas puskesmas lengkap dengan deskripsi.
- **Ambil Antrian Online:** Wizard pendaftaran antrian mandiri + Cetak Tiket.
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
- **Cetak Resep:** Fitur cetak salinan resep untuk pasien.
- **Manajemen Stok:** CRUD data obat, pantau stok minimum & kedaluwarsa.

### D. Manajemen Admin (Back Office)
*Akses: Admin, Kepala Puskesmas*
- **Dasbor Operasional:** Statistik real-time & Visual Charts.
- **Manajemen SDM:** Pendaftaran pegawai & akun login.
- **Manajemen Konten (CMS):** Kelola Artikel Edukasi & Fasilitas untuk web publik.
- **Manajemen Poli & Klaster:** Pengaturan unit layanan yang terstruktur berdasarkan Klaster ILP.
- **Laporan:**
    - Laporan Kunjungan Harian/Bulanan.
    - Laporan 10 Besar Penyakit (Grafik + Tabel).

## 4. Keamanan & Performa
- **Transaction Handling:** Penyimpanan data kritis menggunakan Database Transaction.
- **Role-Based Access Control:** Validasi akses ketat per modul.
- **Real-Time UI:** Livewire untuk interaksi tanpa reload (SPA).