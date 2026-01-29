# RANGKUMAN FITUR & FUNGSI SISTEM PUSKESMAS JAGAKARSA

Status Dokumen: **FINAL ENTERPRISE SUITE v2.1 (INTEGRATED & SIMULATED)**
Terakhir Diupdate: 29 January 2026

## 1. Ikhtisar Sistem
Sistem Informasi Puskesmas Jagakarsa (SIMPUS) adalah platform terintegrasi berbasis web yang dirancang untuk mendukung operasional Puskesmas modern dengan standar **Integrasi Layanan Primer (ILP)**. Sistem ini menghubungkan seluruh unit kerja mulai dari pendaftaran, pelayanan medis, penunjang, hingga keuangan secara real-time.

## 2. Fitur Unggulan (Enterprise Grade)
- **Full Patient Cycle:** Alur pasien lengkap mulai dari Daftar -> Poli -> Lab -> Farmasi -> Kasir -> Pulang.
- **BPJS Bridging Simulation:** Simulasi pengecekan status kepesertaan BPJS saat pendaftaran pasien.
- **Laboratorium Terintegrasi:** Input hasil pemeriksaan lab yang langsung terhubung ke rekam medis pasien.
- **Billing System (Kasir):** Perhitungan biaya otomatis (Jasa Medis + Obat + Lab + Admin) dan cetak kuitansi.
- **Administrasi Medis:** Penerbitan Surat Keterangan Sakit, Sehat, dan Rujukan.
- **Keamanan Audit:** Perekaman aktivitas pengguna (Audit Trail) untuk akuntabilitas.
- **Layar Antrian TV:** Digital signage dengan panggilan suara otomatis.

## 3. Modul Utama

### A. Halaman Publik (Front Office)
*Akses: Masyarakat Umum*
- **Layar Antrian TV:** Menampilkan nomor yang dipanggil dan status per poli secara real-time.
- **Beranda Interaktif:** Informasi layanan, jadwal dokter, dan statistik.
- **CMS Edukasi Kesehatan:** Portal berita kesehatan.
- **Ambil Antrian Online:** Wizard pendaftaran antrian mandiri + Cetak Tiket.

### B. Pendaftaran & Rekam Medis (Front Desk)
*Akses: Pendaftaran, Dokter*
- **Manajemen Pasien:** CRUD data pasien dengan cek otomatis status BPJS (Simulasi).
- **Pemeriksaan Medis (SOAP):** Anamnesa, Fisik, Diagnosa (ICD-10), Plan.
- **Riwayat Medis Pasien:** Akses histori kunjungan sebelumnya.
- **Order Lab:** Dokter dapat membuat permintaan pemeriksaan laboratorium.
- **Administrasi Surat:** Pembuatan surat sakit/rujukan.

### C. Penunjang Medis (Laboratorium)
*Akses: Petugas Lab*
- **Antrian Lab:** Menerima permintaan dari dokter.
- **Input Hasil:** Memasukkan parameter hasil lab (Hb, Leukosit, dll).

### D. Farmasi (Apotek)
*Akses: Apoteker*
- **E-Prescribing:** Menerima resep elektronik dari dokter.
- **Manajemen Stok:** Integrasi stok obat real-time (FIFO).

### E. Keuangan (Kasir)
*Akses: Kasir*
- **Billing Otomatis:** Agregasi biaya dari Poli, Lab, dan Farmasi.
- **Pembayaran:** Tunai/Non-Tunai & Cetak Struk/Kuitansi.

### F. Manajemen Admin (Back Office)
*Akses: Admin, Kepala Puskesmas*
- **Dashboard Visual:** Grafik kunjungan mingguan & status antrian.
- **Manajemen SDM & Poli:** Pengaturan pegawai dan unit layanan ILP.
- **Laporan:**
    - Laporan Kunjungan & Pendapatan.
    - Laporan 10 Besar Penyakit (Morbiditas).
- **Audit Log:** Memantau aktivitas sistem.

## 4. Keamanan & Performa
- **Transaction Handling:** Database Transaction (ACID) untuk integritas data medis & keuangan.
- **Role-Based Access Control:** Validasi akses ketat per modul.
- **Real-Time UI:** Livewire untuk interaksi tanpa reload (SPA).
