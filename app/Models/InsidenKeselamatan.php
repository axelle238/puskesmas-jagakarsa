<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsidenKeselamatan extends Model
{
    protected $table = 'insiden_keselamatan';
    protected $guarded = [];

    protected $casts = [
        'tanggal_kejadian' => 'date',
    ];

    public function pelapor()
    {
        return $this->belongsTo(Pegawai::class, 'id_pelapor');
    }
}
