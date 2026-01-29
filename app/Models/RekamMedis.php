<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model RekamMedis
 * Menyimpan data pemeriksaan medis pasien (SOAP).
 */
class RekamMedis extends Model
{
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
        'tindakan',      // Legacy/Summary field
        'resep_obat',    // Legacy/Summary field
        'catatan_tambahan'
    ];

    /**
     * Pasien yang diperiksa
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    /**
     * Dokter yang memeriksa
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_dokter');
    }

    /**
     * Poli tempat pemeriksaan
     */
    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    /**
     * Daftar obat yang diresepkan (Pivot Table)
     */
    public function resepDetail(): BelongsToMany
    {
        return $this->belongsToMany(Obat::class, 'resep_obat', 'id_rekam_medis', 'id_obat')
                    ->withPivot(['jumlah', 'aturan_pakai', 'status_pengambilan', 'catatan_apoteker'])
                    ->withTimestamps();
    }

    /**
     * Daftar tindakan yang dilakukan (Pivot Table)
     */
    public function tindakanDetail(): BelongsToMany
    {
        return $this->belongsToMany(TindakanMedis::class, 'detail_tindakan_medis', 'id_rekam_medis', 'id_tindakan_medis')
                    ->withPivot(['biaya_saat_ini', 'catatan_tindakan'])
                    ->withTimestamps();
    }

    /**
     * Daftar permintaan laboratorium
     */
    public function permintaanLab(): HasMany
    {
        return $this->hasMany(PermintaanLab::class, 'id_rekam_medis');
    }
}
