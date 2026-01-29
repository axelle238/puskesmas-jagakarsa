<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanLab extends Model
{
    protected $table = 'permintaan_lab';
    protected $guarded = [];

    protected $casts = [
        'waktu_permintaan' => 'datetime',
        'waktu_selesai' => 'datetime',
        'biaya_lab' => 'decimal:2',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'id_dokter_pengirim');
    }

    public function petugas()
    {
        return $this->belongsTo(Pegawai::class, 'id_petugas_lab');
    }

    public function hasil()
    {
        return $this->hasMany(HasilLab::class, 'id_permintaan_lab');
    }
}