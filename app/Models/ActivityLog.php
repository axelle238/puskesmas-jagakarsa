<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'id_pengguna',
        'action',
        'module',
        'description',
        'ip_address'
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
    
    // Helper statis untuk mencatat log dengan mudah
    public static function catat($action, $module, $description)
    {
        self::create([
            'id_pengguna' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip()
        ]);
    }
}
