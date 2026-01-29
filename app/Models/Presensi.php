<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime', // Auto cast ke Carbon agar bisa format jam
        'jam_pulang' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
