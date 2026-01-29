<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakanMedis extends Model
{
    protected $table = 'tindakan_medis';
    
    protected $fillable = [
        'kode_tindakan',
        'nama_tindakan',
        'id_poli',
        'tarif',
        'aktif'
    ];

    protected $casts = [
        'tarif' => 'decimal:2',
        'aktif' => 'boolean'
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}