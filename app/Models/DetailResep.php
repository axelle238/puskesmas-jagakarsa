<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    protected $table = 'detail_resep';

    protected $fillable = [
        'id_rekam_medis',
        'id_obat',
        'jumlah',
        'dosis',
        'catatan',
        'harga_satuan_saat_ini'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }
}
