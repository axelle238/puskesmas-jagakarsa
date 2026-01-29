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
        'misi'
    ];
}
