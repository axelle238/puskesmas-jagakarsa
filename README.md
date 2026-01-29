# Sistem Informasi Manajemen Puskesmas (SIMPUS) Jagakarsa

Sistem Informasi Manajemen Puskesmas (SIMPUS) berbasis web yang komprehensif (Enterprise Resource Planning) untuk mengelola operasional Puskesmas Kecamatan Jagakarsa.

## Fitur Utama

### 1. Pelayanan (Front-Office)
- **Pendaftaran Online**: Pasien dapat mendaftar mandiri via web publik.
- **Anjungan Mandiri (Kiosk)**: Antrian digital terintegrasi.
- **Jadwal Dokter Publik**: Informasi jadwal praktik dokter real-time.

### 2. Pelayanan Medis (Clinical)
- **Rekam Medis Elektronik (RME)**: Pencatatan SOAP standar medis.
- **E-Resep**: Resep digital langsung ke Farmasi.
- **Laboratorium**: Order dan input hasil lab terintegrasi.

### 3. Penunjang (Back-Office)
- **Farmasi**: Manajemen stok obat, expired date, dan kartu stok.
- **Kasir**: Pembayaran tunai/non-tunai dan klaim BPJS.
- **Kepegawaian**: Data pegawai dan presensi digital (Clock-in/Clock-out).
- **Keuangan**: Buku Kas Umum (BKU) otomatis dari transaksi harian.

### 4. Manajemen Strategis
- **Dashboard Eksekutif**: Pantauan real-time kunjungan, omset, dan stok kritis.
- **Pelaporan**: Laporan Kunjungan (Grafik), Morbiditas (10 Besar Penyakit), dan Kinerja.
- **Audit Trail**: Log aktivitas pengguna untuk keamanan data.

---

## Instalasi & Konfigurasi

### Persyaratan
- PHP 8.2+
- MySQL / MariaDB
- Composer
- Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/axelle238/puskesmas-jagakarsa.git
   cd puskesmas-jagakarsa
   ```

2. **Install Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Database**
   - Salin file `.env.example` ke `.env`
   - Sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

4. **Migrasi & Seeding Data**
   Jalankan perintah berikut untuk membuat tabel dan mengisi data demo lengkap:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Server**
   ```bash
   php artisan serve
   ```

---

## Akun Demo (Login)

Berikut adalah akun yang dapat digunakan untuk pengujian (Password: `password`):

| Role | Email | Deskripsi |
| :--- | :--- | :--- |
| **Administrator** | `admin@puskesmas.id` | Akses penuh ke seluruh modul sistem |
| **Kepala Puskesmas** | `kapus@puskesmas.id` | Dashboard eksekutif dan laporan |
| **Dokter Umum** | `budi@puskesmas.id` | Pemeriksaan medis, resep, surat sakit |
| **Dokter Gigi** | `siti@puskesmas.id` | Pemeriksaan gigi |
| **Apoteker** | `rudi@puskesmas.id` | Kelola resep dan stok obat |
| **Pendaftaran** | `daftar@puskesmas.id` | Registrasi pasien dan antrian |
| **Kasir** | `jokokasir@puskesmas.id` | Pembayaran tagihan |

---

## Struktur Folder Penting

- `app/Livewire`: Logika aplikasi (Backend)
- `resources/views/livewire`: Tampilan antarmuka (Frontend)
- `database/migrations`: Skema database
- `routes/web.php`: Definisi rute aplikasi

---

**Dibuat dengan ❤️ untuk Transformasi Digital Kesehatan Indonesia.**
