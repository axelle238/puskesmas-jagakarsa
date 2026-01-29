<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Barang (Elektronik, Medis, Mebel, dll)
        Schema::create('kategori_barang', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('nama_kategori');
            $tabel->text('deskripsi')->nullable();
            $tabel->timestamps();
        });

        // 2. Data Barang / Aset
        Schema::create('barang', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_kategori');
            $tabel->string('kode_barang')->unique(); // Contoh: INV-2024-001
            $tabel->string('nama_barang');
            $tabel->string('merk')->nullable();
            $tabel->string('nomor_seri')->nullable();
            $tabel->year('tahun_perolehan')->nullable();
            $tabel->decimal('harga_perolehan', 15, 2)->default(0);
            $tabel->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang', 'dihapuskan'])->default('baik');
            $tabel->string('lokasi_penyimpanan')->nullable(); // Nama Ruangan
            $tabel->text('keterangan')->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_kategori')->references('id')->on('kategori_barang')->onDelete('cascade');
        });

        // 3. Riwayat Mutasi / Perubahan Kondisi Barang
        Schema::create('mutasi_barang', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('id_barang');
            $tabel->unsignedBigInteger('id_pegawai')->nullable(); // Penanggung jawab mutasi
            $tabel->date('tanggal_mutasi');
            $tabel->string('jenis_mutasi'); // Pindah Lokasi, Perubahan Kondisi, Perbaikan
            $tabel->string('keterangan_lama')->nullable(); // Misal: Lokasi lama / Kondisi lama
            $tabel->string('keterangan_baru')->nullable(); // Misal: Lokasi baru / Kondisi baru
            $tabel->text('catatan')->nullable();
            $tabel->timestamps();

            $tabel->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
            $tabel->foreign('id_pegawai')->references('id')->on('pegawai')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_barang');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('kategori_barang');
    }
};
