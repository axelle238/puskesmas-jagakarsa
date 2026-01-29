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
            $tabel->integer('nomor_antrian');
            $tabel->string('kode_antrian'); // Contoh: UM-001
            $tabel->enum('status', ['menunggu', 'dipanggil', 'sedang_diperiksa', 'selesai', 'batal'])->default('menunggu');
            $tabel->date('tanggal_kunjungan');
            $tabel->timestamp('waktu_check_in')->nullable(); // Saat pasien datang fisik
            $tabel->timestamp('waktu_dipanggil')->nullable();
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
