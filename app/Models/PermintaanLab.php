<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermintaanLab extends Model
{
    protected $table = 'permintaan_lab';
    protected $guarded = [];

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_dokter_pengirim');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_petugas_lab');
    }

    public function hasil(): HasMany
    {
        return $this->hasMany(HasilLab::class, 'id_permintaan_lab');
    }
}
