<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPenyuluhan extends Model
{
    protected $table = 'jadwal_penyuluhan';
    protected $guarded = [];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'jam_mulai' => 'datetime', // Casting ke Carbon untuk format jam
        'jam_selesai' => 'datetime',
    ];

    public function petugas()
    {
        return $this->belongsTo(Pegawai::class, 'id_petugas');
    }

    public function laporan()
    {
        return $this->hasOne(LaporanPenyuluhan::class, 'jadwal_id');
    }
}
