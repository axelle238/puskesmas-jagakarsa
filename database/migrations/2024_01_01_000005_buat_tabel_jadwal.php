<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_dokter', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_dokter'); // Relasi ke pegawai
            $tabel->unsignedBigInteger('id_poli');
            $tabel->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $tabel->time('jam_mulai');
            $tabel->time('jam_selesai');
            $tabel->integer('kuota_pasien')->default(20);
            $tabel->boolean('aktif')->default(true);
            $tabel->timestamps();

            $tabel->foreign('id_dokter')->references('id')->on('pegawai')->onDelete('cascade');
            $tabel->foreign('id_poli')->references('id')->on('poli')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokter');
    }
};
