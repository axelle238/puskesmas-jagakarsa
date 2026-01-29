<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model TindakanMedis
 * Mengelola daftar tarif dan jenis tindakan medis.
 */
class TindakanMedis extends Model
{
    protected $table = 'tindakan_medis';

    protected $fillable = [
        'kode_tindakan',
        'nama_tindakan',
        'id_poli',
        'tarif'
    ];

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}
