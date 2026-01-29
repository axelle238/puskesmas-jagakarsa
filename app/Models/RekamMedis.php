<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\MencatatAktivitas; // Import Trait

/**
 * Model RekamMedis
 * Menyimpan data pemeriksaan medis pasien (SOAP).
 */
class RekamMedis extends Model
{
    use MencatatAktivitas; // Aktifkan Audit Trail

    protected $table = 'rekam_medis';

    protected $fillable = [
        'id_pasien',
        'id_dokter',
        'id_poli',
        'id_antrian',
        'keluhan_utama',
        'riwayat_penyakit',
        'subjektif',
        'objektif',
        'asesmen',
        'plan',
        'diagnosis_kode',
        'tindakan',      // Summary field
        'resep_obat',    // Summary field
        'catatan_tambahan'
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_dokter');
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function antrian(): BelongsTo
    {
        return $this->belongsTo(Antrian::class, 'id_antrian');
    }

    // Relasi ke Detail Resep (HasMany)
    public function detailResep(): HasMany
    {
        return $this->hasMany(DetailResep::class, 'id_rekam_medis');
    }

    // Relasi ke Permintaan Lab
    public function permintaanLab(): HasMany
    {
        return $this->hasMany(PermintaanLab::class, 'id_rekam_medis');
    }
}
