<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\MencatatAktivitas; // Import Trait

class Pasien extends Model
{
    use HasFactory, MencatatAktivitas; // Gunakan Trait

    protected $table = 'pasien';

    protected $guarded = []; // Ubah ke guarded biar fleksibel

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