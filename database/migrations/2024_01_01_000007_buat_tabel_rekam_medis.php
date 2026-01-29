<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_pasien');
            $tabel->unsignedBigInteger('id_dokter'); // Relasi ke pegawai
            $tabel->unsignedBigInteger('id_poli');
            $tabel->unsignedBigInteger('id_antrian')->nullable(); // Link ke antrian hari itu
            
            $tabel->text('keluhan_utama');
            $tabel->text('riwayat_penyakit')->nullable();
            
            // SOAP Data
            $tabel->text('subjektif')->nullable(); // S
            $tabel->text('objektif')->nullable(); // O (Tensi, Suhu, BB, TB)
            $tabel->text('asesmen')->nullable(); // A (Diagnosa)
            $tabel->text('plan')->nullable(); // P (Tindakan/Resep)
            
            $tabel->text('diagnosis_kode')->nullable(); // ICD-10 Code
            $tabel->text('tindakan')->nullable();
            $tabel->text('resep_obat')->nullable(); // JSON atau Text sederhana dulu
            $tabel->text('catatan_tambahan')->nullable();
            
            $tabel->timestamps();

            $tabel->foreign('id_pasien')->references('id')->on('pasien')->onDelete('cascade');
            $tabel->foreign('id_dokter')->references('id')->on('pegawai')->onDelete('cascade');
            $tabel->foreign('id_poli')->references('id')->on('poli')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
