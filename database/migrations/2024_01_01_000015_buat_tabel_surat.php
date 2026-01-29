<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('surat_keterangan')) {
            Schema::create('surat_keterangan', function (Blueprint $tabel) {
                $tabel->id();
                $tabel->string('no_surat')->unique();
                $tabel->enum('jenis_surat', ['sakit', 'sehat']);
                $tabel->unsignedBigInteger('id_pasien');
                $tabel->unsignedBigInteger('id_dokter');
                $tabel->date('tanggal_mulai')->nullable(); // Untuk sakit
                $tabel->integer('lama_istirahat')->nullable(); // Hari
                $tabel->string('keperluan')->nullable(); // Untuk sehat
                $tabel->text('catatan_fisik')->nullable(); // BB, TB, Tensi, Buta Warna
                $tabel->timestamps();

                $tabel->foreign('id_pasien')->references('id')->on('pasien')->onDelete('cascade');
                $tabel->foreign('id_dokter')->references('id')->on('pegawai')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keterangan');
    }
};
