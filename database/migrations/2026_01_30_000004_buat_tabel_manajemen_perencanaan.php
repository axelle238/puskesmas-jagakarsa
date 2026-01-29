<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // 1. Tabel Utama Kegiatan Perencanaan (RUK/RPK)
        Schema::create('kegiatan_perencanaan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->year('tahun_anggaran');
            $tabel->enum('sumber_dana', ['APBD', 'BOK', 'JKN', 'BLUD', 'LAINNYA']);
            $tabel->string('nama_kegiatan');
            $tabel->text('tujuan')->nullable();
            $tabel->string('sasaran')->nullable(); // Target group
            $tabel->string('target_kinerja')->nullable(); // Kuantitatif
            
            // Keuangan
            $tabel->decimal('pagu_anggaran', 15, 2)->default(0); // Usulan awal
            $tabel->decimal('anggaran_disetujui', 15, 2)->nullable(); // Setelah RPK
            
            // Pelaksanaan
            $tabel->string('waktu_pelaksanaan')->nullable(); // Misal: "Januari - Maret"
            $tabel->unsignedBigInteger('penanggung_jawab_id')->nullable(); // Pegawai
            
            $tabel->enum('status', ['usulan', 'disetujui', 'ditolak', 'berjalan', 'selesai'])->default('usulan');
            $tabel->timestamps();

            $tabel->foreign('penanggung_jawab_id')->references('id')->on('pegawai')->onDelete('set null');
        });

        // 2. Tabel Realisasi Anggaran (Monitoring Serapan)
        Schema::create('realisasi_anggaran', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('kegiatan_id');
            $tabel->date('tanggal_realisasi');
            $tabel->decimal('jumlah', 15, 2);
            $tabel->string('uraian_pengeluaran');
            $tabel->string('bukti_dokumen')->nullable(); // Path file
            $tabel->unsignedBigInteger('diinput_oleh')->nullable();
            $tabel->timestamps();

            $tabel->foreign('kegiatan_id')->references('id')->on('kegiatan_perencanaan')->onDelete('cascade');
            $tabel->foreign('diinput_oleh')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_anggaran');
        Schema::dropIfExists('kegiatan_perencanaan');
    }
};
