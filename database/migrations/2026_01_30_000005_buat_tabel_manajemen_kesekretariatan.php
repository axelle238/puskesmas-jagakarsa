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
        // 1. Surat Masuk
        Schema::create('surat_masuk', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nomor_surat')->unique(); // Nomor dari instansi pengirim
            $tabel->string('asal_surat');
            $tabel->date('tanggal_surat'); // Tanggal yg tertera di surat
            $tabel->date('tanggal_diterima'); // Tanggal masuk ke TU
            $tabel->string('perihal');
            $tabel->string('file_dokumen')->nullable(); // Scan PDF
            $tabel->enum('sifat', ['biasa', 'penting', 'rahasia'])->default('biasa');
            $tabel->enum('status', ['menunggu_disposisi', 'didisposisi', 'selesai'])->default('menunggu_disposisi');
            $tabel->unsignedBigInteger('id_pencatat')->nullable(); // Petugas TU
            $tabel->timestamps();

            $tabel->foreign('id_pencatat')->references('id')->on('pengguna')->onDelete('set null');
        });

        // 2. Disposisi Surat (Tracking perintah Kapus)
        Schema::create('disposisi_surat', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('surat_masuk_id');
            $tabel->unsignedBigInteger('dari_pegawai_id'); // Biasanya Kapus / Kasubag TU
            $tabel->unsignedBigInteger('ke_pegawai_id'); // Penerima disposisi
            $tabel->string('instruksi'); // Misal: "Tindak lanjuti", "Arsipkan"
            $tabel->string('catatan_tambahan')->nullable();
            $tabel->date('batas_waktu')->nullable();
            $tabel->boolean('sudah_dibaca')->default(false);
            $tabel->enum('status_penyelesaian', ['belum', 'proses', 'selesai'])->default('belum');
            $tabel->timestamps();

            $tabel->foreign('surat_masuk_id')->references('id')->on('surat_masuk')->onDelete('cascade');
            $tabel->foreign('dari_pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
            $tabel->foreign('ke_pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });

        // 3. Surat Keluar
        Schema::create('surat_keluar', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nomor_surat')->unique(); // Nomor internal Puskesmas
            $tabel->string('tujuan_surat');
            $tabel->date('tanggal_surat');
            $tabel->string('perihal');
            $tabel->string('file_dokumen')->nullable(); // Arsip PDF
            $tabel->enum('sifat', ['biasa', 'penting', 'rahasia'])->default('biasa');
            $tabel->unsignedBigInteger('id_pembuat')->nullable(); // Pembuat konsep
            $tabel->timestamps();

            $tabel->foreign('id_pembuat')->references('id')->on('pegawai')->onDelete('set null');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
        Schema::dropIfExists('disposisi_surat');
        Schema::dropIfExists('surat_masuk');
    }
};