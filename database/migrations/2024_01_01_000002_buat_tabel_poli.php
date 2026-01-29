<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poli', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_poli'); // Contoh: Poli Umum, Poli Gigi
            $tabel->string('kode_poli')->unique(); // Contoh: UM, GG
            $tabel->text('deskripsi')->nullable();
            $tabel->string('lokasi_ruangan')->nullable();
            $tabel->boolean('aktif')->default(true);
            $tabel->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poli');
    }
};
