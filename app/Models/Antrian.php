<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrian';

    protected $fillable = [
        'id_pasien',
        'id_poli',
        'id_jadwal',
        'nomor_antrian', // A-001
        'tanggal_antrian',
        'status', // menunggu, dipanggil, diperiksa, selesai, batal
        'waktu_checkin',
        'waktu_selesai'
    ];

    protected $casts = [
        'tanggal_antrian' => 'date',
        'waktu_checkin' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalDokter::class, 'id_jadwal');
    }
}