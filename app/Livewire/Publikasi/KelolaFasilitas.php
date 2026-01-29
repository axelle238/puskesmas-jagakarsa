<?php

namespace App\Livewire\Publikasi;

use App\Models\Fasilitas;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class KelolaFasilitas extends Component
{
    use WithPagination, WithFileUploads;

    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idFasilitasDiedit = null;

    // Form
    public $nama_fasilitas;
    public $deskripsi;
    public $foto;
    public $foto_lama;

    protected $rules = [
        'nama_fasilitas' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'foto' => 'nullable|image|max:2048',
    ];

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $f = Fasilitas::findOrFail($id);
        $this->idFasilitasDiedit = $id;
        $this->nama_fasilitas = $f->nama_fasilitas;
        $this->deskripsi = $f->deskripsi;
        $this->foto_lama = $f->foto;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate();

        $pathFoto = $this->foto_lama;
        if ($this->foto) {
            $pathFoto = $this->foto->store('fasilitas', 'public');
        }

        $data = [
            'nama_fasilitas' => $this->nama_fasilitas,
            'deskripsi' => $this->deskripsi,
            'foto' => $pathFoto
        ];

        if ($this->modeEdit) {
            if (!$this->foto) unset($data['foto']);
            Fasilitas::find($this->idFasilitasDiedit)->update($data);
            session()->flash('sukses', 'Fasilitas diperbarui.');
        } else {
            Fasilitas::create($data);
            session()->flash('sukses', 'Fasilitas ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        Fasilitas::find($id)->delete();
        session()->flash('sukses', 'Fasilitas dihapus.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nama_fasilitas', 'deskripsi', 'foto', 'foto_lama', 'idFasilitasDiedit']);
    }

    public function render()
    {
        $fasilitas = Fasilitas::paginate(10);
        return view('livewire.publikasi.kelola-fasilitas', [
            'dataFasilitas' => $fasilitas
        ])->layout('components.layouts.admin', ['title' => 'Kelola Fasilitas']);
    }
}