<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $guarded = [];

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_kasir');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailTagihan::class, 'id_tagihan');
    }
}
