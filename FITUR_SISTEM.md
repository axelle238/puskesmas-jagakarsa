# SISTEM INFORMASI PUSKESMAS JAGAKARSA (SIMPUS)

Status: **FINAL COMPLETED VERSION 2.2**
Teknologi: Laravel 12, Livewire 4, Tailwind CSS
Database: MySQL

## 1. Ikhtisar Sistem
Platform terintegrasi untuk operasional Puskesmas modern dengan standar **Integrasi Layanan Primer (ILP)** Kemenkes RI. Mengelola seluruh alur pasien mulai dari pendaftaran online hingga pengambilan obat.

## 2. Fitur Unggulan (Key Features)

### A. Layanan Publik (Front Office)
*   **Pendaftaran Antrian Online:** Pasien mendaftar mandiri via web.
*   **Layar Antrian TV (Digital Signage):** Tampilan ruang tunggu dengan panggilan suara otomatis.
*   **Portal Informasi:** Jadwal dokter, info fasilitas, dan artikel edukasi kesehatan.

### B. Layanan Klinis (Medical Record)
*   **Rekam Medis Elektronik (EMR):** Pencatatan SOAP, Tanda Vital, dan Riwayat Pasien.
*   **Integrasi Farmasi:** Resep elektronik yang langsung memotong stok obat.
*   **Laporan Morbiditas:** Analisis otomatis 10 Besar Penyakit (ICD-10).

### C. Manajemen Admin (Back Office)
*   **Dashboard Visual:** Grafik kunjungan harian/mingguan real-time.
*   **Manajemen SDM & Akses:** Kelola dokter, perawat, dan staf.
*   **Pengaturan Poli & Jadwal:** Fleksibel sesuai Klaster ILP.
*   **Keuangan & Kasir:** Billing system otomatis dari Poli/Lab/Farmasi.

## 3. Panduan Instalasi (Deployment)

### Persyaratan Server
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi
1.  **Clone Repository:**
    ```bash
    git clone https://github.com/axelle238/puskesmas-jagakarsa.git
    cd puskesmas-jagakarsa
    ```

2.  **Install Dependencies:**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Konfigurasi Environment:**
    ```bash
    cp .env.example .env
    # Edit .env sesuaikan database (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
    php artisan key:generate
    ```

4.  **Migrasi & Seeding Data (Wajib):**
    ```bash
    php artisan migrate:fresh --seed
    ```
    *Perintah ini akan membuat tabel dan mengisi data awal (Admin, Poli, Obat, dll).*

5.  **Jalankan Server:**
    ```bash
    php artisan serve
    ```

## 4. Akun Demo (Default)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@puskesmas.id` | `admin123` |
| **Dokter** | `dokter@puskesmas.id` | `dokter123` |
| **Apoteker** | `farmasi@puskesmas.id` | `farmasi123` |

---
**Hak Cipta & Pengembang**
Dikembangkan oleh Tim IT Puskesmas Jagakarsa (AI Assisted).