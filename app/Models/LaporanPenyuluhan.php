<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenyuluhan extends Model
{
    protected $table = 'laporan_penyuluhan';
    protected $guarded = [];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPenyuluhan::class, 'jadwal_id');
    }

    public function penginput()
    {
        return $this->belongsTo(Pengguna::class, 'diinput_oleh');
    }
}
