<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogBackup extends Model
{
    protected $table = 'log_backup';
    protected $guarded = [];

    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class, 'id_pembuat');
    }
}
