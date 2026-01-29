<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalDokter extends Model
{
    use HasFactory;

    protected $table = 'jadwal_dokter';

    protected $fillable = [
        'id_dokter',
        'id_poli',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kuota_pasien',
        'aktif',
    ];

    // Relasi
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_dokter');
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}
