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
            $tabel->enum('peran', ['admin', 'dokter', 'perawat', 'apoteker', 'pendaftaran', 'pasien'])->default('pasien');
            $tabel->string('no_telepon')->nullable();
            $tabel->text('alamat')->nullable();
            $tabel->string('foto_profil')->nullable();
            $tabel->rememberToken()->map('ingat_saya_token'); // Ubah nama kolom token jika memungkinkan, tapi helper ini fix. Kita pakai alias manual jika perlu raw. Tapi default remember_token lebih aman untuk auth laravel. Saya akan biarkan default remember_token untuk kompatibilitas Auth, tapi di model kita mapping. 
            // Koreksi: Helper rememberToken() membuat kolom 'remember_token'. Agar 100% indo, kita buat manual string.
            $tabel->string('ingat_saya_token', 100)->nullable();
            $tabel->timestamps();
        });

        // Tabel sesi untuk driver database
        Schema::create('sesi', function (Blueprint $tabel) {
            $tabel->string('id')->primary();
            $tabel->foreignId('user_id')->nullable()->index(); // Laravel default auth uses user_id
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
        Schema::dropIfExists('pengguna');
        Schema::dropIfExists('sesi');
        Schema::dropIfExists('cache');
    }
};
