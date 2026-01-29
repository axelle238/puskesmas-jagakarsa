<?php

namespace App\Livewire\Laboratorium;

use App\Models\HasilLab;
use App\Models\PermintaanLab;
use App\Models\RekamMedis;
use Livewire\Component;

/**
 * Class InputHasil
 * Modul untuk Analis Lab mengelola permintaan dan input hasil pemeriksaan.
 */
class InputHasil extends Component
{
    public $activeTab = 'antrian'; // antrian, riwayat
    
    // Modal Input
    public $tampilkanModal = false;
    public $permintaanTerpilih;
    
    // Form Hasil
    public $parameter;
    public $nilai_hasil;
    public $nilai_rujukan;
    public $satuan;
    
    // List Hasil Sementara
    public $hasilList = [];

    // Filter
    public $cari = '';

    public function pilihPermintaan($id)
    {
        $this->permintaanTerpilih = PermintaanLab::with(['rekamMedis.pasien', 'hasil'])->find($id);
        
        if ($this->permintaanTerpilih) {
            // Load hasil existing jika ada
            $this->hasilList = $this->permintaanTerpilih->hasil->toArray();
            $this->tampilkanModal = true;
        }
    }

    public function tambahParameter()
    {
        $this->validate([
            'parameter' => 'required',
            'nilai_hasil' => 'required',
        ]);

        $this->hasilList[] = [
            'parameter' => $this->parameter,
            'nilai_hasil' => $this->nilai_hasil,
            'nilai_rujukan' => $this->nilai_rujukan,
            'satuan' => $this->satuan
        ];

        // Reset form kecil
        $this->reset(['parameter', 'nilai_hasil', 'nilai_rujukan', 'satuan']);
    }

    public function hapusParameter($index)
    {
        unset($this->hasilList[$index]);
        $this->hasilList = array_values($this->hasilList);
    }

    public function simpanHasil()
    {
        if (!$this->permintaanTerpilih) return;

        // Hapus hasil lama (reset)
        HasilLab::where('id_permintaan_lab', $this->permintaanTerpilih->id)->delete();

        foreach ($this->hasilList as $h) {
            HasilLab::create([
                'id_permintaan_lab' => $this->permintaanTerpilih->id,
                'parameter' => $h['parameter'],
                'nilai_hasil' => $h['nilai_hasil'],
                'nilai_rujukan' => $h['nilai_rujukan'] ?? '-',
                'satuan' => $h['satuan'] ?? '-'
            ]);
        }

        // Update status permintaan
        $this->permintaanTerpilih->status = 'selesai';
        $this->permintaanTerpilih->waktu_selesai = now();
        $this->permintaanTerpilih->id_petugas_lab = auth()->user()->pegawai->id ?? null;
        $this->permintaanTerpilih->save();

        session()->flash('sukses', 'Hasil laboratorium berhasil disimpan.');
        $this->tutupModal();
    }

    public function buatPermintaanBaru()
    {
        // TODO: Fitur jika pasien datang langsung membawa kertas pengantar
        // Untuk MVP, kita fokus proses antrian saja.
    }

    public function buatDummy()
    {
        // Cari rekam medis hari ini untuk ditambahkan permintaan lab
        $rm = RekamMedis::latest()->first();
        
        if ($rm) {
            PermintaanLab::create([
                'no_permintaan' => 'LAB-' . date('Ymd') . '-' . rand(100, 999),
                'id_rekam_medis' => $rm->id,
                'id_dokter_pengirim' => $rm->id_dokter,
                'catatan_permintaan' => 'Cek Darah Lengkap (Dummy)',
                'status' => 'menunggu',
                'waktu_permintaan' => now(),
                'biaya_lab' => 50000 // Contoh biaya
            ]);
            session()->flash('sukses', 'Permintaan lab dummy berhasil dibuat.');
        } else {
            session()->flash('error', 'Tidak ada data rekam medis untuk membuat dummy.');
        }
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->permintaanTerpilih = null;
        $this->hasilList = [];
    }

    public function render()
    {
        $permintaan = PermintaanLab::with(['rekamMedis.pasien', 'dokter.pengguna'])
            ->where('no_permintaan', 'like', '%' . $this->cari . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.laboratorium.input-hasil', [
            'daftarPermintaan' => $permintaan
        ])->layout('components.layouts.admin', ['title' => 'Laboratorium']);
    }
}