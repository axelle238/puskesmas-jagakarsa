<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Surat Keterangan Medis
        Schema::create('surat_keterangan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('no_surat')->unique();
            $tabel->unsignedBigInteger('id_pasien');
            $tabel->unsignedBigInteger('id_dokter');
            $tabel->enum('jenis_surat', ['Surat Keterangan Sakit', 'Surat Keterangan Sehat', 'Rujukan']);
            
            // Atribut Surat
            $tabel->date('tanggal_mulai');
            $tabel->integer('lama_istirahat')->nullable(); // Dalam hari (untuk surat sakit)
            $tabel->text('keterangan_tambahan')->nullable(); // TB/BB/Buta Warna (untuk surat sehat)
            $tabel->string('tujuan_rujukan')->nullable(); // RS Tujuan (untuk rujukan)
            
            $tabel->timestamps();

            $tabel->foreign('id_pasien')->references('id')->on('pasien')->onDelete('cascade');
            $tabel->foreign('id_dokter')->references('id')->on('pegawai')->onDelete('cascade');
        });

        // Tabel Audit Log (Keamanan)
        Schema::create('activity_logs', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_pengguna')->nullable(); // Siapa yang melakukan
            $tabel->string('action'); // CREATE, UPDATE, DELETE, LOGIN
            $tabel->string('module'); // Pasien, Obat, RekamMedis
            $tabel->text('description'); // Detail aktivitas
            $tabel->string('ip_address')->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('surat_keterangan');
    }
};
