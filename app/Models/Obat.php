<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MencatatAktivitas;

class Obat extends Model
{
    use MencatatAktivitas;

    protected $table = 'obat';
    protected $guarded = [];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
    ];
}