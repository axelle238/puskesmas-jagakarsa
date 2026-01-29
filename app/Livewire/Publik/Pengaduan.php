<?php

namespace App\Livewire\Publik;

use App\Models\PengaduanMasyarakat;
use Livewire\Component;
use Livewire\WithFileUploads;

class Pengaduan extends Component
{
    use WithFileUploads;

    public $nama_pelapor;
    public $no_telepon;
    public $email;
    public $kategori = 'Layanan Medis';
    public $isi_laporan;
    public $bukti_foto;

    protected $rules = [
        'nama_pelapor' => 'required|min:3',
        'no_telepon' => 'required|numeric',
        'kategori' => 'required',
        'isi_laporan' => 'required|min:10',
        'bukti_foto' => 'nullable|image|max:2048'
    ];

    public function kirim()
    {
        $this->validate();

        $path = null;
        if ($this->bukti_foto) {
            $path = $this->bukti_foto->store('pengaduan', 'public');
        }

        PengaduanMasyarakat::create([
            'nama_pelapor' => $this->nama_pelapor,
            'no_telepon' => $this->no_telepon,
            'email' => $this->email,
            'kategori' => $this->kategori,
            'isi_laporan' => $this->isi_laporan,
            'bukti_foto' => $path,
            'status' => 'masuk'
        ]);

        session()->flash('sukses', 'Pengaduan Anda telah terkirim. Terima kasih atas masukan Anda.');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.publik.pengaduan')
            ->layout('components.layouts.publik', ['title' => 'Layanan Pengaduan']);
    }
}
