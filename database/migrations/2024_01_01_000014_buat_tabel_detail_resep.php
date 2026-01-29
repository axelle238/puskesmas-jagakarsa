<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel pivot untuk menghubungkan Rekam Medis dengan Obat secara mendetail
        Schema::create('detail_resep', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_rekam_medis');
            $tabel->unsignedBigInteger('id_obat');
            $tabel->integer('jumlah');
            $tabel->string('dosis')->nullable(); // Contoh: 3x1 Sesudah Makan
            $tabel->text('catatan')->nullable(); // Contoh: Gerus halus
            $tabel->decimal('harga_satuan_saat_ini', 12, 2)->default(0); // Snapshot harga
            $tabel->timestamps();

            $tabel->foreign('id_rekam_medis')->references('id')->on('rekam_medis')->onDelete('cascade');
            $tabel->foreign('id_obat')->references('id')->on('obat')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_resep');
    }
};
