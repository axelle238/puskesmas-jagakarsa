<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposisiSurat extends Model
{
    protected $table = 'disposisi_surat';
    protected $guarded = [];

    protected $casts = [
        'batas_waktu' => 'date',
    ];

    public function surat()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function dariPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'dari_pegawai_id');
    }

    public function kePegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ke_pegawai_id');
    }
}
