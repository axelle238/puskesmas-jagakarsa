<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikel_edukasi', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('judul');
            $tabel->string('slug')->unique(); // Untuk URL SEO friendly
            $tabel->text('ringkasan');
            $tabel->longText('konten');
            $tabel->string('kategori')->default('Umum'); // Umum, Ibu & Anak, Gizi, dll
            $tabel->string('gambar_sampul')->nullable(); // Path gambar
            $tabel->unsignedBigInteger('id_penulis');
            $tabel->boolean('publikasi')->default(true);
            $tabel->timestamps();

            $tabel->foreign('id_penulis')->references('id')->on('pengguna')->onDelete('cascade');
        });

        Schema::create('fasilitas', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_fasilitas');
            $tabel->text('deskripsi');
            $tabel->string('foto')->nullable();
            $tabel->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
        Schema::dropIfExists('artikel_edukasi');
    }
};
