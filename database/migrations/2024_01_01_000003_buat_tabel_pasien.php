<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_pengguna')->nullable(); // Jika pasien mendaftar akun online
            $tabel->string('no_rekam_medis')->unique(); // Format: RM-202401-001
            $tabel->string('nik', 16)->unique();
            $tabel->string('nama_lengkap');
            $tabel->string('tempat_lahir');
            $tabel->date('tanggal_lahir');
            $tabel->enum('jenis_kelamin', ['L', 'P']);
            $tabel->text('alamat_lengkap');
            $tabel->string('golongan_darah', 5)->nullable();
            $tabel->string('no_bpjs')->nullable();
            $tabel->string('no_telepon_darurat')->nullable();
            $tabel->string('nama_kontak_darurat')->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
