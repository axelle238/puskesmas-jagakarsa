<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait MencatatAktivitas
{
    protected static function bootMencatatAktivitas()
    {
        static::created(function ($model) {
            self::logAktivitas('CREATE', 'Menambahkan data baru', $model);
        });

        static::updated(function ($model) {
            self::logAktivitas('UPDATE', 'Memperbarui data', $model);
        });

        static::deleted(function ($model) {
            self::logAktivitas('DELETE', 'Menghapus data', $model);
        });
    }

    protected static function logAktivitas($action, $description, $model)
    {
        // Skip jika tidak ada user login (misal seeder), kecuali jika ingin mencatat system action
        $userId = Auth::id(); 
        
        $properties = null;
        if ($action === 'UPDATE') {
            $properties = [
                'old' => $model->getOriginal(),
                'new' => $model->getAttributes(),
            ];
        } elseif ($action === 'CREATE') {
            $properties = [
                'new' => $model->getAttributes(),
            ];
        } elseif ($action === 'DELETE') {
            $properties = [
                'old' => $model->getOriginal(),
            ];
        }

        ActivityLog::create([
            'id_pengguna' => $userId,
            'action' => $action,
            'description' => $description . ' pada ' . class_basename($model),
            'target_model' => get_class($model),
            'target_id' => $model->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
