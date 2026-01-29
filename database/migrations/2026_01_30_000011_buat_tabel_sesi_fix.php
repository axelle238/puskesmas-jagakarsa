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
        if (!Schema::hasTable('sesi')) {
            Schema::create('sesi', function (Blueprint $tabel) {
                $tabel->string('id')->primary();
                $tabel->foreignId('user_id')->nullable()->index();
                $tabel->string('ip_address', 45)->nullable();
                $tabel->text('user_agent')->nullable();
                $tabel->longText('payload');
                $tabel->integer('last_activity')->index();
            });
        }

        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $tabel) {
                $tabel->string('key')->primary();
                $tabel->mediumText('value');
                $tabel->integer('expiration');
            });
        }

        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $tabel) {
                $tabel->string('key')->primary();
                $tabel->string('owner');
                $tabel->integer('expiration');
            });
        }
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        // Kita tidak drop sesi di sini secara otomatis karena mungkin dibuat oleh migrasi lain (pengguna),
        // tapi untuk konsistensi di file fix ini, kita bisa drop jika kita yakin ini yang membuatnya.
        // Namun, lebih aman membiarkannya atau drop if exists.
        Schema::dropIfExists('sesi');
    }
};
