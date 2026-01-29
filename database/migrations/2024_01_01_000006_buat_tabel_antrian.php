<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrian', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_pasien');
            $tabel->unsignedBigInteger('id_jadwal');
            $tabel->unsignedBigInteger('id_poli'); // Denormalisasi untuk query cepat
            $tabel->string('nomor_antrian'); // String, contoh: A-001
            $tabel->date('tanggal_antrian');
            $tabel->enum('status', ['menunggu', 'dipanggil', 'diperiksa', 'selesai', 'batal'])->default('menunggu');
            $tabel->timestamp('waktu_checkin')->nullable();
            $tabel->timestamp('waktu_selesai')->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_pasien')->references('id')->on('pasien')->onDelete('cascade');
            $tabel->foreign('id_jadwal')->references('id')->on('jadwal_dokter')->onDelete('cascade');
            $tabel->foreign('id_poli')->references('id')->on('poli')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};