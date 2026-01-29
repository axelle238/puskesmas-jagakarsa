<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';

    protected $fillable = [
        'id_pengguna',
        'no_rekam_medis',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_lengkap',
        'golongan_darah',
        'no_bpjs',
        'no_telepon_darurat',
        'nama_kontak_darurat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function antrian(): HasMany
    {
        return $this->hasMany(Antrian::class, 'id_pasien');
    }

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'id_pasien');
    }
}
