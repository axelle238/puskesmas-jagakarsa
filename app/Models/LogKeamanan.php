<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogKeamanan extends Model
{
    protected $table = 'log_keamanan';
    protected $guarded = [];

    protected $casts = [
        'detail' => 'array',
    ];
}
