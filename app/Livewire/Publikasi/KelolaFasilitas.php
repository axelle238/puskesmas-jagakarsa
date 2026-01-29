<?php

namespace App\Livewire\Publikasi;

use App\Models\Fasilitas;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Kelola Fasilitas')]
class KelolaFasilitas extends Component
{
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idFasilitas;

    public $nama_fasilitas, $deskripsi;

    protected $rules = [
        'nama_fasilitas' => 'required|string|max:255',
        'deskripsi' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.publikasi.kelola-fasilitas', [
            'fasilitas' => Fasilitas::all()
        ])->layout('components.layouts.admin');
    }

    public function tambah()
    {
        $this->reset(['nama_fasilitas', 'deskripsi', 'idFasilitas']);
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $fasilitas = Fasilitas::find($id);
        $this->idFasilitas = $id;
        $this->nama_fasilitas = $fasilitas->nama_fasilitas;
        $this->deskripsi = $fasilitas->deskripsi;
        
        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate();

        if ($this->modeEdit) {
            Fasilitas::find($this->idFasilitas)->update([
                'nama_fasilitas' => $this->nama_fasilitas,
                'deskripsi' => $this->deskripsi,
            ]);
        } else {
            Fasilitas::create([
                'nama_fasilitas' => $this->nama_fasilitas,
                'deskripsi' => $this->deskripsi,
            ]);
        }

        $this->tampilkanModal = false;
        session()->flash('pesan', 'Data fasilitas berhasil disimpan.');
    }

    public function hapus($id)
    {
        Fasilitas::find($id)->delete();
        session()->flash('pesan', 'Fasilitas dihapus.');
    }
}
