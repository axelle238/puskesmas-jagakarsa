<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Whitelist IP (Akses Terbatas)
        Schema::create('whitelist_ip', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('ip_address');
            $tabel->string('keterangan')->nullable(); // Misal: Komputer Pendaftaran 1
            $tabel->boolean('aktif')->default(true);
            $tabel->timestamps();
        });

        // 2. Log Keamanan (Login Failed, Blocked Access)
        Schema::create('log_keamanan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('ip_address');
            $tabel->string('user_agent')->nullable();
            $tabel->string('event'); // Login Gagal, Akses Ditolak, Percobaan Brute Force
            $tabel->string('target_email')->nullable(); // Email yang dicoba untuk login
            $tabel->json('detail')->nullable();
            $tabel->timestamps();
        });

        // 3. Pengaturan Keamanan Global
        Schema::create('pengaturan_keamanan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->boolean('batasi_ip_login_admin')->default(false);
            $tabel->integer('maksimal_percobaan_login')->default(5);
            $tabel->integer('durasi_blokir_menit')->default(30);
            $tabel->boolean('wajib_ganti_password_berkala')->default(true);
            $tabel->integer('masa_berlaku_password_hari')->default(90);
            $tabel->timestamps();
        });

        // 4. Log Backup Database
        Schema::create('log_backup', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_file');
            $tabel->string('path_penyimpanan');
            $tabel->string('ukuran_file');
            $tabel->enum('status', ['sukses', 'gagal'])->default('sukses');
            $tabel->unsignedBigInteger('id_pembuat')->nullable(); // Admin yang trigger manual
            $tabel->timestamps();

            $tabel->foreign('id_pembuat')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_backup');
        Schema::dropIfExists('pengaturan_keamanan');
        Schema::dropIfExists('log_keamanan');
        Schema::dropIfExists('whitelist_ip');
    }
};
