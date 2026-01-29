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
        Schema::create('presensi', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_pegawai');
            $tabel->date('tanggal');
            $tabel->time('jam_masuk')->nullable();
            $tabel->time('jam_pulang')->nullable();
            $tabel->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
            $tabel->text('keterangan')->nullable(); // Untuk alasan izin/sakit
            $tabel->string('lokasi_koordinat')->nullable(); // GPS Lat,Long (Opsional)
            $tabel->timestamps();

            $tabel->foreign('id_pegawai')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
