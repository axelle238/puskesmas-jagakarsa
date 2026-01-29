<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('activity_log')) {
            Schema::create('activity_log', function (Blueprint $tabel) {
                $tabel->id();
                $tabel->unsignedBigInteger('id_pengguna')->nullable();
                $tabel->string('action'); 
                $tabel->string('description'); 
                $tabel->string('ip_address')->nullable();
                $tabel->timestamps();

                $tabel->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
