<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model KlasterIlp
 * Mengelola data Klaster Integrasi Layanan Primer (ILP).
 */
class KlasterIlp extends Model
{
    protected $table = 'klaster_ilp';

    protected $fillable = [
        'nama_klaster',
        'deskripsi_layanan'
    ];
}
