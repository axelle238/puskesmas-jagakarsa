<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorMutu extends Model
{
    protected $table = 'indikator_mutu';
    protected $guarded = [];

    public function hasil()
    {
        return $this->hasMany(HasilMutu::class, 'indikator_id');
    }
}
