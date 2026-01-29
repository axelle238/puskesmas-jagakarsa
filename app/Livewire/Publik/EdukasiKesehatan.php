<?php

namespace App\Livewire\Publik;

use App\Models\ArtikelEdukasi;
use Livewire\Component;
use Livewire\WithPagination;

class EdukasiKesehatan extends Component
{
    use WithPagination;

    public $kategori = '';
    public $cari = '';

    public function updatedKategori()
    {
        $this->resetPage();
    }

    public function updatedCari()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ArtikelEdukasi::where('publikasi', true);

        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        if ($this->cari) {
            $query->where('judul', 'like', '%' . $this->cari . '%');
        }

        $artikel = $query->latest()->paginate(9);

        return view('livewire.publik.edukasi-kesehatan', [
            'artikel' => $artikel
        ])->layout('components.layouts.publik', ['title' => 'Edukasi Kesehatan']);
    }
}