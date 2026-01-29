<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_lengkap');
            $tabel->string('email')->unique();
            $tabel->timestamp('email_terverifikasi_pada')->nullable();
            $tabel->string('sandi');
            // Update enum peran agar mencakup semua role yang digunakan di Seeder
            $tabel->enum('peran', [
                'admin', 
                'dokter', 
                'perawat', 
                'apoteker', 
                'pendaftaran', 
                'pasien',
                'kasir',   // Ditambahkan
                'analis',  // Ditambahkan
                'kapus'    // Ditambahkan
            ])->default('pasien');
            $tabel->string('no_telepon')->nullable();
            $tabel->text('alamat')->nullable();
            $tabel->string('foto_profil')->nullable();
            $tabel->string('ingat_saya_token', 100)->nullable();
            $tabel->timestamps();
        });

        // Tabel sesi untuk driver database
        Schema::create('sesi', function (Blueprint $tabel) {
            $tabel->string('id')->primary();
            $tabel->foreignId('user_id')->nullable()->index(); 
            $tabel->string('ip_address', 45)->nullable();
            $tabel->text('user_agent')->nullable();
            $tabel->longText('payload');
            $tabel->integer('last_activity')->index();
        });

        // Tabel cache untuk driver database
        Schema::create('cache', function (Blueprint $tabel) {
            $tabel->string('key')->primary();
            $tabel->mediumText('value');
            $tabel->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $tabel) {
            $tabel->string('key')->primary();
            $tabel->string('owner');
            $tabel->integer('expiration');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sesi');
        Schema::dropIfExists('pengguna');
    }
};
