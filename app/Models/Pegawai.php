<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Pegawai
 * Menyimpan data detail pegawai/nakes yang terhubung dengan akun login.
 */
class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'id_pengguna',
        'nip',
        'str',
        'sip',
        'tanggal_masuk',
        'jabatan',
        'spesialisasi'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    /**
     * Akun login milik pegawai ini
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    /**
     * Rekam medis yang ditangani oleh dokter ini
     */
    public function rekamMedisDitangani(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'id_dokter');
    }

    /**
     * Mengambil nama lengkap dari relasi pengguna
     */
    public function getNamaLengkapAttribute()
    {
        return $this->pengguna->nama_lengkap ?? '-';
    }
}