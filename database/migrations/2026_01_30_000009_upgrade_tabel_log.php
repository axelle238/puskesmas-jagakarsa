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
        Schema::table('activity_log', function (Blueprint $tabel) {
            $tabel->string('target_model')->nullable()->after('description'); // Misal: App\Models\Pasien
            $tabel->unsignedBigInteger('target_id')->nullable()->after('target_model');
            $tabel->json('properties')->nullable()->after('target_id'); // Data Lama & Baru
            $tabel->string('user_agent')->nullable()->after('ip_address');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $tabel) {
            $tabel->dropColumn(['target_model', 'target_id', 'properties', 'user_agent']);
        });
    }
};
