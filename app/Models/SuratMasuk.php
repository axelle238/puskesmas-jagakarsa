<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    protected $guarded = [];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_diterima' => 'date',
    ];

    public function pencatat()
    {
        return $this->belongsTo(Pengguna::class, 'id_pencatat');
    }

    public function disposisi()
    {
        return $this->hasMany(DisposisiSurat::class, 'surat_masuk_id');
    }
}
