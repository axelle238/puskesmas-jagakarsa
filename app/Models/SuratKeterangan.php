<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MencatatAktivitas; // Tambahkan Trait

class SuratKeterangan extends Model
{
    use MencatatAktivitas; // Aktifkan Audit Trail

    protected $table = 'surat_keterangan';
    protected $guarded = [];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'catatan_fisik' => 'array', // Simpan BB/TB/Tensi sebagai JSON
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'id_dokter');
    }
}
