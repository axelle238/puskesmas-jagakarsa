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
    public $modeCetak = false;
    
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

    protected $messages = [
        'nik.required' => 'NIK wajib diisi.',
        'lama_istirahat.required' => 'Lama istirahat wajib diisi.',
        'keperluan.required' => 'Keperluan surat wajib diisi.'
    ];

    public function mount()
    {
        $this->tanggal_mulai = date('Y-m-d');
    }

    public function cariPasien()
    {
        $this->validate(['nik' => 'required']);
        // Cari by NIK atau No RM
        $this->pasien = Pasien::where('nik', $this->nik)
            ->orWhere('no_rekam_medis', $this->nik)
            ->first();
            
        if (!$this->pasien) {
            session()->flash('error', 'Pasien tidak ditemukan. Pastikan NIK atau No. RM benar.');
        } else {
            $this->resetErrorBag();
        }
    }

    public function simpanSurat()
    {
        if (!$this->pasien) {
            $this->addError('nik', 'Silakan cari pasien terlebih dahulu.');
            return;
        }

        $rules = ['jenis_surat' => 'required'];
        if ($this->jenis_surat == 'sakit') {
            $rules['lama_istirahat'] = 'required|numeric|min:1';
            $rules['tanggal_mulai'] = 'required|date';
        } else {
            $rules['bb'] = 'required|numeric';
            $rules['tb'] = 'required|numeric';
            $rules['tensi'] = 'required';
            $rules['keperluan'] = 'required';
        }
        $this->validate($rules);

        // Generate No Surat (Auto Increment per bulan)
        $bulanRomawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $count = SuratKeterangan::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count() + 1;
        $noSurat = sprintf("%03d/SK/%s/%s", $count, $bulanRomawi, $tahun);
        
        $data = [
            'no_surat' => $noSurat,
            'jenis_surat' => $this->jenis_surat,
            'id_pasien' => $this->pasien->id,
            'id_dokter' => auth()->user()->pegawai->id ?? 1, // Fallback ID 1 jika testing
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
        $this->modeCetak = true;
        session()->flash('sukses', 'Surat berhasil dibuat. Silakan cetak.');
    }

    public function resetForm()
    {
        $this->reset(['nik', 'pasien', 'jenis_surat', 'lama_istirahat', 'bb', 'tb', 'tensi', 'keperluan', 'modeCetak', 'suratBaru']);
        $this->tanggal_mulai = date('Y-m-d');
    }

    private function getRomawi($n) {
        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romawi[$n];
    }

    public function render()
    {
        return view('livewire.medis.buat-surat')->layout('components.layouts.admin', ['title' => 'Buat Surat Keterangan']);
    }
}