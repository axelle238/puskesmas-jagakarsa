<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilMutu extends Model
{
    protected $table = 'hasil_mutu';
    protected $guarded = [];

    public function indikator()
    {
        return $this->belongsTo(IndikatorMutu::class, 'indikator_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Pegawai::class, 'id_petugas');
    }
}
