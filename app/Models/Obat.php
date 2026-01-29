<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Obat
 * Mengelola data persediaan obat di farmasi.
 */
class Obat extends Model
{
    protected $table = 'obat';
    
    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'kategori',
        'satuan',
        'stok_saat_ini',
        'stok_minimum',
        'harga_satuan',
        'tanggal_kedaluwarsa',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'harga_satuan' => 'decimal:2',
    ];

    // Cek apakah stok menipis
    public function perluRestock(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }
}
