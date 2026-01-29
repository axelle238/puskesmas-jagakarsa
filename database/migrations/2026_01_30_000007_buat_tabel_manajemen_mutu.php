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
        // 1. Tabel Indikator Mutu (INM/IMP)
        Schema::create('indikator_mutu', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('judul_indikator'); // Contoh: Kepatuhan Kebersihan Tangan
            $tabel->enum('tipe', ['nasional', 'prioritas', 'unit']); 
            $tabel->string('unit_terkait'); // Poli Umum, Farmasi, dll
            $tabel->float('target_capaian'); // Dalam Persen, misal 85.5
            $tabel->timestamps();
        });

        // 2. Tabel Hasil Ukur Indikator (Bulanan)
        Schema::create('hasil_mutu', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->unsignedBigInteger('indikator_id');
            $tabel->string('bulan'); // Format: 01-2024
            $tabel->integer('pembilang'); // Numerator
            $tabel->integer('penyebut'); // Denominator
            $tabel->float('capaian'); // Hasil %
            $tabel->text('analisis')->nullable();
            $tabel->text('tindak_lanjut')->nullable();
            $tabel->unsignedBigInteger('id_petugas');
            $tabel->timestamps();

            $tabel->foreign('indikator_id')->references('id')->on('indikator_mutu')->onDelete('cascade');
            $tabel->foreign('id_petugas')->references('id')->on('pegawai')->onDelete('cascade');
        });

        // 3. Tabel Insiden Keselamatan Pasien (IKP)
        Schema::create('insiden_keselamatan', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->date('tanggal_kejadian');
            $tabel->time('waktu_kejadian');
            $tabel->string('lokasi_kejadian');
            $tabel->enum('jenis_insiden', ['KPC', 'KNC', 'KTC', 'KTD', 'Sentinel']); 
            // KPC: Kondisi Potensial Cedera, KNC: Nyaris Cedera, KTC: Tidak Cedera, KTD: Tidak Diharapkan
            $tabel->text('kronologi');
            $tabel->text('tindakan_segera');
            $tabel->enum('grading_risiko', ['biru', 'hijau', 'kuning', 'merah'])->default('biru');
            $tabel->unsignedBigInteger('id_pelapor');
            $tabel->enum('status_investigasi', ['belum', 'proses', 'selesai'])->default('belum');
            $tabel->timestamps();

            $tabel->foreign('id_pelapor')->references('id')->on('pegawai')->onDelete('cascade');
        });

        // 4. Tabel Register Risiko
        Schema::create('register_risiko', function (Blueprint $tabel) {
            $tabel->id();
            $tabel->string('unit_kerja');
            $tabel->text('pernyataan_risiko'); // Apa yang mungkin terjadi
            $tabel->text('penyebab');
            $tabel->text('dampak');
            $tabel->integer('nilai_kemungkinan'); // 1-5
            $tabel->integer('nilai_dampak'); // 1-5
            $tabel->string('tingkat_risiko'); // Rendah, Sedang, Tinggi, Ekstrem (Hasil kali)
            $tabel->text('pengendalian_saat_ini');
            $tabel->text('rencana_penanganan');
            $tabel->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_risiko');
        Schema::dropIfExists('insiden_keselamatan');
        Schema::dropIfExists('hasil_mutu');
        Schema::dropIfExists('indikator_mutu');
    }
};
