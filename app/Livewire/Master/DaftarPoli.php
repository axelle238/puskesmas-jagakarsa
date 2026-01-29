<?php

namespace App\Livewire\Master;

use App\Models\Poli;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Manajemen Poli')]
class DaftarPoli extends Component
{
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idPoli;

    public $nama_poli, $deskripsi, $lokasi_ruangan;

    protected $rules = [
        'nama_poli' => 'required|string|max:255',
        'lokasi_ruangan' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.master.daftar-poli', [
            'polis' => Poli::withCount('rekamMedis')->get()
        ])->layout('components.layouts.admin');
    }

    public function tambah()
    {
        $this->reset(['nama_poli', 'deskripsi', 'lokasi_ruangan', 'idPoli']);
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $poli = Poli::find($id);
        $this->idPoli = $id;
        $this->nama_poli = $poli->nama_poli;
        $this->deskripsi = $poli->deskripsi;
        $this->lokasi_ruangan = $poli->lokasi_ruangan;
        
        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate();

        if ($this->modeEdit) {
            Poli::find($this->idPoli)->update([
                'nama_poli' => $this->nama_poli,
                'deskripsi' => $this->deskripsi,
                'lokasi_ruangan' => $this->lokasi_ruangan,
            ]);
            session()->flash('pesan', 'Poli berhasil diperbarui.');
        } else {
            Poli::create([
                'nama_poli' => $this->nama_poli,
                'deskripsi' => $this->deskripsi,
                'lokasi_ruangan' => $this->lokasi_ruangan,
            ]);
            session()->flash('pesan', 'Poli baru berhasil ditambahkan.');
        }

        $this->tampilkanModal = false;
    }

    public function hapus($id)
    {
        Poli::find($id)->delete();
        session()->flash('pesan', 'Poli berhasil dihapus.');
    }
}
