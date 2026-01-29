<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiAnggaran extends Model
{
    protected $table = 'realisasi_anggaran';
    protected $guarded = [];

    protected $casts = [
        'tanggal_realisasi' => 'date',
        'jumlah' => 'decimal:2'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanPerencanaan::class, 'kegiatan_id');
    }

    public function penginput()
    {
        return $this->belongsTo(Pengguna::class, 'diinput_oleh');
    }
}
