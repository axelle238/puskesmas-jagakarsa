<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaduanMasyarakat extends Model
{
    protected $table = 'pengaduan_masyarakat';
    protected $guarded = [];

    public function petugas()
    {
        return $this->belongsTo(Pengguna::class, 'id_petugas');
    }
}
