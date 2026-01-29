<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilInstansi extends Model
{
    protected $table = 'profil_instansi';

    protected $fillable = [
        'nama_instansi',
        'alamat',
        'telepon',
        'email',
        'logo_path',
        'visi',
        'misi',
        // Kolom Baru CMS
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'nama_kepala_puskesmas',
        'sambutan_kepala',
        'foto_kepala',
        'link_video_profil'
    ];
}