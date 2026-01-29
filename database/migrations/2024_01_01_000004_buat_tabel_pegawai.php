<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_pengguna'); // Relasi ke tabel pengguna
            $tabel->string('nip')->unique()->nullable(); // Nomor Induk Pegawai
            $tabel->string('str')->nullable(); // Surat Tanda Registrasi (untuk Nakes)
            $tabel->string('sip')->nullable(); // Surat Izin Praktik (untuk Dokter)
            $tabel->date('tanggal_masuk')->nullable();
            $tabel->string('jabatan')->nullable();
            $tabel->string('spesialisasi')->nullable(); // Untuk dokter spesialis
            $tabel->timestamps();

            $tabel->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
