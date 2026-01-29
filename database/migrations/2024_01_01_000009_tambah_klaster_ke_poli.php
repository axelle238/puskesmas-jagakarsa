<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poli', function (Blueprint $tabel) {
            $tabel->unsignedBigInteger('id_klaster')->nullable()->after('nama_poli');
            $tabel->foreign('id_klaster')->references('id')->on('klaster_ilp')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('poli', function (Blueprint $tabel) {
            $tabel->dropForeign(['id_klaster']);
            $tabel->dropColumn('id_klaster');
        });
    }
};
