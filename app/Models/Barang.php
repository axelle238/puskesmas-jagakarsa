<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $guarded = [];

    protected $casts = [
        'harga_perolehan' => 'decimal:2',
        'tahun_perolehan' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }

    public function mutasi()
    {
        return $this->hasMany(MutasiBarang::class, 'id_barang')->orderBy('tanggal_mutasi', 'desc');
    }
}
