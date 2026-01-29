<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeterangan extends Model
{
    protected $table = 'surat_keterangan';

    protected $fillable = [
        'no_surat',
        'id_pasien',
        'id_dokter',
        'jenis_surat',
        'tanggal_mulai',
        'lama_istirahat',
        'keterangan_tambahan',
        'tujuan_rujukan'
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_dokter');
    }
}
