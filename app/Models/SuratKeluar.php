<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $guarded = [];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function pembuat()
    {
        return $this->belongsTo(Pegawai::class, 'id_pembuat');
    }
}