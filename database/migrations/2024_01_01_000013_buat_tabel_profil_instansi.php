<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_instansi', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_instansi')->default('Puskesmas Jagakarsa');
            $tabel->text('alamat')->nullable();
            $tabel->string('telepon')->nullable();
            $tabel->string('email')->nullable();
            $tabel->string('logo_path')->nullable();
            $tabel->text('visi')->nullable();
            $tabel->text('misi')->nullable();
            $tabel->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_instansi');
    }
};
