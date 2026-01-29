<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Permintaan & Hasil Laboratorium
        Schema::create('permintaan_lab', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('no_permintaan')->unique(); // LAB-20240101-001
            $tabel->unsignedBigInteger('id_rekam_medis');
            $tabel->unsignedBigInteger('id_dokter_pengirim');
            $tabel->unsignedBigInteger('id_petugas_lab')->nullable();
            $tabel->text('catatan_permintaan')->nullable(); // Misal: Cek Darah Lengkap
            $tabel->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $tabel->dateTime('waktu_permintaan');
            $tabel->dateTime('waktu_selesai')->nullable();
            $tabel->decimal('biaya_lab', 12, 2)->default(0);
            $tabel->timestamps();

            $tabel->foreign('id_rekam_medis')->references('id')->on('rekam_medis')->onDelete('cascade');
            $tabel->foreign('id_dokter_pengirim')->references('id')->on('pegawai')->onDelete('cascade');
            $tabel->foreign('id_petugas_lab')->references('id')->on('pegawai')->onDelete('set null');
        });

        Schema::create('hasil_lab', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_permintaan_lab');
            $tabel->string('parameter'); // Hemoglobin, Leukosit, dll
            $tabel->string('nilai_hasil');
            $tabel->string('nilai_rujukan')->nullable(); // Nilai normal
            $tabel->string('satuan')->nullable(); // g/dL, /mm3
            $tabel->timestamps();

            $tabel->foreign('id_permintaan_lab')->references('id')->on('permintaan_lab')->onDelete('cascade');
        });

        // 2. Tabel Kasir / Billing
        Schema::create('tagihan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('no_tagihan')->unique(); // INV-20240101-001
            $tabel->unsignedBigInteger('id_rekam_medis');
            $tabel->unsignedBigInteger('id_kasir')->nullable();
            $tabel->decimal('total_biaya', 12, 2);
            $tabel->decimal('jumlah_bayar', 12, 2)->default(0);
            $tabel->decimal('kembalian', 12, 2)->default(0);
            $tabel->enum('status_bayar', ['belum_lunas', 'lunas', 'gratis'])->default('belum_lunas'); // Gratis untuk BPJS
            $tabel->enum('metode_bayar', ['tunai', 'qris', 'debit', 'bpjs'])->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_rekam_medis')->references('id')->on('rekam_medis')->onDelete('cascade');
            $tabel->foreign('id_kasir')->references('id')->on('pegawai')->onDelete('set null');
        });

        Schema::create('detail_tagihan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_tagihan');
            $tabel->string('item'); // Nama tindakan/obat
            $tabel->string('kategori'); // Tindakan, Obat, Lab, Admin
            $tabel->integer('jumlah')->default(1);
            $tabel->decimal('harga_satuan', 12, 2);
            $tabel->decimal('subtotal', 12, 2);
            $tabel->timestamps();

            $tabel->foreign('id_tagihan')->references('id')->on('tagihan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tagihan');
        Schema::dropIfExists('tagihan');
        Schema::dropIfExists('hasil_lab');
        Schema::dropIfExists('permintaan_lab');
    }
};
