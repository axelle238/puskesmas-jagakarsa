<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtikelEdukasi extends Model
{
    protected $table = 'artikel_edukasi';

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'kategori',
        'gambar_sampul',
        'id_penulis',
        'publikasi'
    ];

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_penulis');
    }
}
