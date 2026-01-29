<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Pengguna
 * Menggantikan model User bawaan Laravel agar sesuai bahasa Indonesia.
 */
class Pengguna extends \Illuminate\Foundation\Auth\User
{
    protected $table = 'pengguna';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'sandi',
        'peran',
        'no_telepon',
        'alamat',
        'foto_profil',
        'ingat_saya_token'
    ];

    protected $hidden = [
        'sandi',
        'ingat_saya_token',
    ];

    protected $casts = [
        'email_terverifikasi_pada' => 'datetime',
        'sandi' => 'hashed',
    ];

    // Override kolom password default Laravel
    public function getAuthPassword()
    {
        return $this->sandi;
    }

    // Override kolom remember token default Laravel
    public function getRememberTokenName()
    {
        return 'ingat_saya_token';
    }

    // Cek peran
    public function memilikiPeran($peran)
    {
        return $this->peran === $peran;
    }

    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'id_pengguna');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_pengguna');
    }
}