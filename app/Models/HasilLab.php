<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilLab extends Model
{
    protected $table = 'hasil_lab';
    protected $guarded = [];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanLab::class, 'id_permintaan_lab');
    }
}