<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('kode_obat')->unique();
            $tabel->string('nama_obat');
            $tabel->string('kategori'); // Obat Keras, Bebas, dll
            $tabel->string('satuan'); // Tablet, Botol, Strip
            $tabel->integer('stok_saat_ini')->default(0);
            $tabel->integer('stok_minimum')->default(10);
            $tabel->decimal('harga_satuan', 12, 2);
            $tabel->date('tanggal_kedaluwarsa');
            $tabel->text('keterangan')->nullable();
            $tabel->timestamps();
        });

        Schema::create('tindakan_medis', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('kode_tindakan')->unique();
            $tabel->string('nama_tindakan');
            $tabel->unsignedBigInteger('id_poli'); // Tindakan ini milik poli mana
            $tabel->decimal('tarif', 12, 2);
            $tabel->timestamps();

            $tabel->foreign('id_poli')->references('id')->on('poli')->onDelete('cascade');
        });

        Schema::create('klaster_ilp', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_klaster'); // Klaster 1 (Manajemen), 2 (Ibu & Anak), 3 (Dewasa), 4 (Lansia), 5 (Lintas)
            $tabel->text('deskripsi_layanan');
            $tabel->timestamps();
        });

        // Tabel pivot/transaksi untuk resep dalam rekam medis
        Schema::create('resep_obat', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_rekam_medis');
            $tabel->unsignedBigInteger('id_obat');
            $tabel->integer('jumlah');
            $tabel->string('aturan_pakai'); // 3x1 sesudah makan
            $tabel->text('catatan_apoteker')->nullable();
            $tabel->enum('status_pengambilan', ['menunggu', 'disiapkan', 'selesai']).default('menunggu');
            $tabel->timestamps();

            $tabel->foreign('id_rekam_medis')->references('id')->on('rekam_medis')->onDelete('cascade');
            $tabel->foreign('id_obat')->references('id')->on('obat')->onDelete('cascade');
        });

        // Tabel pivot/transaksi untuk tindakan dalam rekam medis
        Schema::create('detail_tindakan_medis', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_rekam_medis');
            $tabel->unsignedBigInteger('id_tindakan_medis');
            $tabel->decimal('biaya_saat_ini', 12, 2); // Biaya saat tindakan dilakukan (snapshot)
            $tabel->text('catatan_tindakan')->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_rekam_medis')->references('id')->on('rekam_medis')->onDelete('cascade');
            $tabel->foreign('id_tindakan_medis')->references('id')->on('tindakan_medis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tindakan_medis');
        Schema::dropIfExists('resep_obat');
        Schema::dropIfExists('klaster_ilp');
        Schema::dropIfExists('tindakan_medis');
        Schema::dropIfExists('obat');
    }
};
