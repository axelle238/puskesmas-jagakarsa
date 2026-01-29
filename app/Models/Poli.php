<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Poli
 * Unit layanan kesehatan (Umum, Gigi, KIA, dll).
 */
class Poli extends Model
{
    protected $table = 'poli';

    protected $fillable = [
        'nama_poli',
        'deskripsi',
        'lokasi_ruangan'
    ];

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'id_poli');
    }

    public function tindakan(): HasMany
    {
        return $this->hasMany(TindakanMedis::class, 'id_poli');
    }
}