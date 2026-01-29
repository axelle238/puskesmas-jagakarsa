<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveiKepuasan extends Model
{
    protected $table = 'survei_kepuasan';
    protected $guarded = [];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}
