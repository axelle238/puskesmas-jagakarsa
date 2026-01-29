<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Ubah kolom ENUM untuk menambahkan 'superadmin'
        // Perlu raw SQL karena Doctrine DBAL memiliki keterbatasan dengan ENUM
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('admin', 'dokter', 'perawat', 'apoteker', 'pendaftaran', 'pasien', 'kasir', 'analis', 'kapus', 'superadmin') NOT NULL DEFAULT 'pasien'");
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        // Kembalikan ke state sebelumnya (menghapus superadmin dari opsi, hati-hati jika ada data)
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('admin', 'dokter', 'perawat', 'apoteker', 'pendaftaran', 'pasien', 'kasir', 'analis', 'kapus') NOT NULL DEFAULT 'pasien'");
    }
};