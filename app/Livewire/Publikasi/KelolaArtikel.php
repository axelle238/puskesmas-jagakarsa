<?php

namespace App\Livewire\Publikasi;

use App\Models\ArtikelEdukasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Kelola Artikel Edukasi')]
class KelolaArtikel extends Component
{
    use WithPagination;

    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idArtikel;

    public $judul, $ringkasan, $konten, $kategori = 'Umum', $publikasi = true;

    protected $rules = [
        'judul' => 'required|string|max:255',
        'ringkasan' => 'required|string|max:500',
        'konten' => 'required|string',
        'kategori' => 'required',
    ];

    public function render()
    {
        return view('livewire.publikasi.kelola-artikel', [
            'artikels' => ArtikelEdukasi::with('penulis')
                ->latest()
                ->paginate(10)
        ])->layout('components.layouts.admin');
    }

    public function tambah()
    {
        $this->reset(['judul', 'ringkasan', 'konten', 'kategori', 'publikasi', 'idArtikel']);
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $artikel = ArtikelEdukasi::find($id);
        $this->idArtikel = $id;
        $this->judul = $artikel->judul;
        $this->ringkasan = $artikel->ringkasan;
        $this->konten = $artikel->konten;
        $this->kategori = $artikel->kategori;
        $this->publikasi = $artikel->publikasi;
        
        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate();

        $data = [
            'judul' => $this->judul,
            'slug' => Str::slug($this->judul) . '-' . Str::random(5),
            'ringkasan' => $this->ringkasan,
            'konten' => $this->konten,
            'kategori' => $this->kategori,
            'publikasi' => $this->publikasi,
            'id_penulis' => Auth::id(),
        ];

        if ($this->modeEdit) {
            unset($data['slug']); // Jangan ubah slug saat edit agar SEO aman
            ArtikelEdukasi::find($this->idArtikel)->update($data);
            session()->flash('pesan', 'Artikel berhasil diperbarui.');
        } else {
            ArtikelEdukasi::create($data);
            session()->flash('pesan', 'Artikel baru diterbitkan.');
        }

        $this->tampilkanModal = false;
    }

    public function hapus($id)
    {
        ArtikelEdukasi::find($id)->delete();
        session()->flash('pesan', 'Artikel dihapus.');
    }
}
