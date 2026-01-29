<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtikelEdukasi extends Model
{
    protected $table = 'artikel_edukasi';
    protected $guarded = [];

    public function penulis()
    {
        return $this->belongsTo(Pengguna::class, 'id_penulis');
    }
}