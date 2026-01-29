<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_instansi', function (Blueprint $tabel) {
            // Konten Hero Section
            $tabel->string('hero_title')->nullable()->after('misi');
            $tabel->text('hero_subtitle')->nullable()->after('hero_title');
            $tabel->string('hero_image')->nullable()->after('hero_subtitle');
            
            // Konten Sambutan
            $tabel->string('nama_kepala_puskesmas')->nullable()->after('hero_image');
            $tabel->text('sambutan_kepala')->nullable()->after('nama_kepala_puskesmas');
            $tabel->string('foto_kepala')->nullable()->after('sambutan_kepala');
            
            // Info Tambahan
            $tabel->string('link_video_profil')->nullable()->after('foto_kepala');
        });
    }

    public function down(): void
    {
        Schema::table('profil_instansi', function (Blueprint $tabel) {
            $tabel->dropColumn([
                'hero_title', 'hero_subtitle', 'hero_image',
                'nama_kepala_puskesmas', 'sambutan_kepala', 'foto_kepala',
                'link_video_profil'
            ]);
        });
    }
};
