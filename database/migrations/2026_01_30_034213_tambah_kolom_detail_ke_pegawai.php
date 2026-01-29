<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('nip');
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->string('status_kepegawaian')->nullable()->after('jabatan'); // PNS, PPPK, Honorer
            $table->string('pendidikan_terakhir')->nullable()->after('status_kepegawaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 
                'tempat_lahir', 
                'tanggal_lahir', 
                'jenis_kelamin', 
                'status_kepegawaian', 
                'pendidikan_terakhir'
            ]);
        });
    }
};