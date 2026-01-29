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
        // 1. Pengaduan Masyarakat
        Schema::create('pengaduan_masyarakat', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_pelapor');
            $tabel->string('no_telepon');
            $tabel->string('email')->nullable();
            $tabel->string('kategori'); // Layanan Medis, Fasilitas, Petugas, dll
            $tabel->text('isi_laporan');
            $tabel->string('bukti_foto')->nullable();
            $tabel->enum('status', ['masuk', 'proses', 'selesai', 'ditolak'])->default('masuk');
            $tabel->text('tanggapan_petugas')->nullable();
            $tabel->unsignedBigInteger('id_petugas')->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_petugas')->references('id')->on('pengguna')->onDelete('set null');
        });

        // 2. Survei Kepuasan Masyarakat (IKM)
        Schema::create('survei_kepuasan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_poli')->nullable(); // Layanan yang dinilai
            $tabel->integer('skor_pelayanan'); // 1-5
            $tabel->integer('skor_fasilitas'); // 1-5
            $tabel->integer('skor_petugas'); // 1-5
            $tabel->text('saran_masukan')->nullable();
            $tabel->ipAddress('ip_address')->nullable(); // Mencegah spam
            $tabel->timestamps();

            $tabel->foreign('id_poli')->references('id')->on('poli')->onDelete('set null');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei_kepuasan');
        Schema::dropIfExists('pengaduan_masyarakat');
    }
};
