<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model KlasterIlp
 * Mengelola data Klaster Integrasi Layanan Primer (ILP).
 */
class KlasterIlp extends Model
{
    protected $table = 'klaster_ilp';

    protected $fillable = [
        'nama_klaster',
        'deskripsi_layanan'
    ];

    public function poli(): HasMany
    {
        return $this->hasMany(Poli::class, 'id_klaster');
    }
}