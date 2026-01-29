<?php

namespace App\Livewire\Medis;

use App\Models\Pasien;
use App\Models\SuratKeterangan;
use Livewire\Component;

class BuatSurat extends Component
{
    public $nik;
    public $pasien;
    public $jenis_surat = 'sakit';
    
    // Form Sakit
    public $lama_istirahat = 3;
    public $tanggal_mulai;

    // Form Sehat
    public $bb;
    public $tb;
    public $tensi;
    public $buta_warna = 'Tidak';
    public $keperluan;

    public $suratBaru;

    public function mount()
    {
        $this->tanggal_mulai = date('Y-m-d');
    }

    public function cariPasien()
    {
        $this->validate(['nik' => 'required']);
        $this->pasien = Pasien::where('nik', $this->nik)->first();
        if (!$this->pasien) {
            session()->flash('error', 'Pasien tidak ditemukan.');
        }
    }

    public function simpanSurat()
    {
        $this->validate([
            'pasien' => 'required',
            'jenis_surat' => 'required'
        ]);

        $noSurat = 'SRT-' . date('Ymd') . '-' . rand(100,999);
        
        $data = [
            'no_surat' => $noSurat,
            'jenis_surat' => $this->jenis_surat,
            'id_pasien' => $this->pasien->id,
            'id_dokter' => auth()->user()->pegawai->id ?? 1,
        ];

        if ($this->jenis_surat == 'sakit') {
            $data['tanggal_mulai'] = $this->tanggal_mulai;
            $data['lama_istirahat'] = $this->lama_istirahat;
        } else {
            $data['keperluan'] = $this->keperluan;
            $data['catatan_fisik'] = [
                'bb' => $this->bb,
                'tb' => $this->tb,
                'tensi' => $this->tensi,
                'buta_warna' => $this->buta_warna
            ];
        }

        $this->suratBaru = SuratKeterangan::create($data);
        session()->flash('sukses', 'Surat berhasil dibuat.');
    }

    public function render()
    {
        return view('livewire.medis.buat-surat')->layout('components.layouts.admin', ['title' => 'Buat Surat Keterangan']);
    }
}