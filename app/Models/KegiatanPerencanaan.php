<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanPerencanaan extends Model
{
    protected $table = 'kegiatan_perencanaan';
    protected $guarded = [];

    // Relasi ke Pegawai (Penanggung Jawab)
    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'penanggung_jawab_id');
    }

    // Relasi ke Realisasi
    public function realisasi()
    {
        return $this->hasMany(RealisasiAnggaran::class, 'kegiatan_id');
    }

    // Helper: Hitung sisa anggaran
    public function getSisaAnggaranAttribute()
    {
        $totalRealisasi = $this->realisasi()->sum('jumlah');
        // Gunakan anggaran disetujui jika ada, jika belum pake pagu usulan (opsional, biasanya pagu gak boleh dipake realisasi sblm disetujui)
        $plafon = $this->anggaran_disetujui ?? 0; 
        return $plafon - $totalRealisasi;
    }
    
    // Helper: Persentase serapan
    public function getPersentaseSerapanAttribute()
    {
        $plafon = $this->anggaran_disetujui ?? 0;
        if ($plafon <= 0) return 0;
        
        $totalRealisasi = $this->realisasi()->sum('jumlah');
        return round(($totalRealisasi / $plafon) * 100, 2);
    }
}
