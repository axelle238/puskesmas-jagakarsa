<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Poli
 * Unit layanan kesehatan (Umum, Gigi, KIA, dll).
 */
class Poli extends Model
{
    protected $table = 'poli';

    protected $fillable = [
        'id_klaster',
        'nama_poli',
        'kode_poli',
        'deskripsi',
        'lokasi_ruangan'
    ];

    public function klaster(): BelongsTo
    {
        return $this->belongsTo(KlasterIlp::class, 'id_klaster');
    }

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'id_poli');
    }

    public function tindakan(): HasMany
    {
        return $this->hasMany(TindakanMedis::class, 'id_poli');
    }
}