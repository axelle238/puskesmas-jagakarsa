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
        // 1. Jadwal Penyuluhan / Kegiatan Promkes
        Schema::create('jadwal_penyuluhan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('topik_kegiatan');
            $tabel->string('lokasi'); // Nama Posyandu, Sekolah, Aula, dll
            $tabel->date('tanggal_kegiatan');
            $tabel->time('jam_mulai');
            $tabel->time('jam_selesai')->nullable();
            $tabel->string('sasaran_peserta'); // Misal: Ibu Hamil, Lansia, Siswa SD
            $tabel->unsignedBigInteger('id_petugas'); // Penanggung Jawab
            $tabel->enum('status', ['rencana', 'terlaksana', 'batal'])->default('rencana');
            $tabel->timestamps();

            $tabel->foreign('id_petugas')->references('id')->on('pegawai')->onDelete('cascade');
        });

        // 2. Laporan Hasil Kegiatan
        Schema::create('laporan_penyuluhan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('jadwal_id');
            $tabel->integer('jumlah_peserta_hadir');
            $tabel->text('materi_disampaikan')->nullable();
            $tabel->text('hasil_evaluasi')->nullable(); // Feedback peserta / tanya jawab
            $tabel->string('foto_dokumentasi')->nullable();
            $tabel->unsignedBigInteger('diinput_oleh')->nullable();
            $tabel->timestamps();

            $tabel->foreign('jadwal_id')->references('id')->on('jadwal_penyuluhan')->onDelete('cascade');
            $tabel->foreign('diinput_oleh')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_penyuluhan');
        Schema::dropIfExists('jadwal_penyuluhan');
    }
};
