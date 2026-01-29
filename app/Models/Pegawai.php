<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MencatatAktivitas;

class Pegawai extends Model
{
    use MencatatAktivitas;

    protected $table = 'pegawai';
    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
