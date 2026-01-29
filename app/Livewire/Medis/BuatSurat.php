<?php

namespace App\Livewire\Medis;

use App\Models\SuratKeterangan;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Title('Buat Surat Keterangan')]
class BuatSurat extends Component
{
    // Search Pasien
    public $cari_pasien = '';
    public $hasil_pencarian = [];
    public $pasien_terpilih = null;

    // Form Surat
    public $jenis_surat = 'Surat Keterangan Sakit';
    public $tanggal_mulai;
    public $lama_istirahat = 1;
    public $keterangan_tambahan;
    public $tujuan_rujukan;

    public function mount()
    {
        $this->tanggal_mulai = date('Y-m-d');
    }

    public function updatedCariPasien()
    {
        if (strlen($this->cari_pasien) > 2) {
            $this->hasil_pencarian = Pasien::where('nama_lengkap', 'like', '%' . $this->cari_pasien . '%')
                ->orWhere('no_rekam_medis', 'like', '%' . $this->cari_pasien . '%')
                ->limit(5)
                ->get();
        } else {
            $this->hasil_pencarian = [];
        }
    }

    public function pilihPasien($id)
    {
        $this->pasien_terpilih = Pasien::find($id);
        $this->cari_pasien = ''; // Reset search
        $this->hasil_pencarian = [];
    }

    public function simpanSurat()
    {
        $this->validate([
            'pasien_terpilih' => 'required',
            'jenis_surat' => 'required',
            'tanggal_mulai' => 'required|date',
        ]);

        $dokter = Pegawai::where('id_pengguna', Auth::id())->first();
        
        if (!$dokter) {
            session()->flash('error', 'Akun Anda tidak terdaftar sebagai tenaga medis.');
            return;
        }

        // Generate No Surat: SK/Tahun/Bulan/Urut
        $count = SuratKeterangan::whereYear('created_at', Carbon::now()->year)->count() + 1;
        $noSurat = 'SK/' . date('Y') . '/' . date('m') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

        SuratKeterangan::create([
            'no_surat' => $noSurat,
            'id_pasien' => $this->pasien_terpilih->id,
            'id_dokter' => $dokter->id,
            'jenis_surat' => $this->jenis_surat,
            'tanggal_mulai' => $this->tanggal_mulai,
            'lama_istirahat' => ($this->jenis_surat == 'Surat Keterangan Sakit') ? $this->lama_istirahat : null,
            'keterangan_tambahan' => $this->keterangan_tambahan,
            'tujuan_rujukan' => ($this->jenis_surat == 'Rujukan') ? $this->tujuan_rujukan : null,
        ]);

        // Catat Audit Log
        ActivityLog::catat('CREATE', 'Surat', "Membuat $this->jenis_surat untuk pasien " . $this->pasien_terpilih->nama_lengkap);

        session()->flash('pesan', 'Surat berhasil dibuat: ' . $noSurat);
        $this->reset(['pasien_terpilih', 'keterangan_tambahan', 'tujuan_rujukan']);
    }

    public function render()
    {
        // List Surat Terakhir yang dibuat user ini
        $riwayatSurat = [];
        $dokter = Pegawai::where('id_pengguna', Auth::id())->first();
        if ($dokter) {
            $riwayatSurat = SuratKeterangan::where('id_dokter', $dokter->id)
                ->with('pasien')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('livewire.medis.buat-surat', [
            'riwayatSurat' => $riwayatSurat
        ])->layout('components.layouts.admin');
    }
}
