<?php

namespace App\Livewire\Publikasi;

use App\Models\ArtikelEdukasi;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class KelolaArtikel extends Component
{
    use WithPagination, WithFileUploads;

    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idArtikelDiedit = null;
    public $cari = '';

    // Form
    public $judul;
    public $kategori = 'Umum';
    public $ringkasan;
    public $konten;
    public $gambar; // Upload file
    public $gambar_lama;
    public $publikasi = true;

    protected $rules = [
        'judul' => 'required|string|max:255',
        'kategori' => 'required',
        'ringkasan' => 'required|string|max:255',
        'konten' => 'required',
        'gambar' => 'nullable|image|max:2048', // Max 2MB
    ];

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $artikel = ArtikelEdukasi::findOrFail($id);
        $this->idArtikelDiedit = $id;
        $this->judul = $artikel->judul;
        $this->kategori = $artikel->kategori;
        $this->ringkasan = $artikel->ringkasan;
        $this->konten = $artikel->konten;
        $this->gambar_lama = $artikel->gambar_sampul;
        $this->publikasi = $artikel->publikasi;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate();

        $pathGambar = $this->gambar_lama;
        if ($this->gambar) {
            // Simpan gambar ke storage public
            $pathGambar = $this->gambar->store('artikel', 'public');
        }

        $data = [
            'judul' => $this->judul,
            'slug' => Str::slug($this->judul) . '-' . Str::random(5),
            'kategori' => $this->kategori,
            'ringkasan' => $this->ringkasan,
            'konten' => $this->konten,
            'gambar_sampul' => $pathGambar,
            'publikasi' => $this->publikasi,
            'id_penulis' => auth()->id()
        ];

        if ($this->modeEdit) {
            // Jangan update slug/penulis jika edit
            unset($data['slug'], $data['id_penulis']);
            if (!$this->gambar) unset($data['gambar_sampul']);
            
            ArtikelEdukasi::find($this->idArtikelDiedit)->update($data);
            session()->flash('sukses', 'Artikel berhasil diperbarui.');
        } else {
            ArtikelEdukasi::create($data);
            session()->flash('sukses', 'Artikel berhasil diterbitkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        ArtikelEdukasi::find($id)->delete();
        session()->flash('sukses', 'Artikel dihapus.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['judul', 'kategori', 'ringkasan', 'konten', 'gambar', 'gambar_lama', 'publikasi', 'idArtikelDiedit']);
    }

    public function render()
    {
        $artikel = ArtikelEdukasi::where('judul', 'like', '%' . $this->cari . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.publikasi.kelola-artikel', [
            'dataArtikel' => $artikel
        ])->layout('components.layouts.admin', ['title' => 'Kelola Artikel']);
    }
}