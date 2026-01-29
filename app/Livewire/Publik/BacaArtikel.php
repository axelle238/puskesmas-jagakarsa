<?php

namespace App\Livewire\Publik;

use App\Models\ArtikelEdukasi;
use Livewire\Component;

class BacaArtikel extends Component
{
    public $artikel;

    public function mount($slug)
    {
        $this->artikel = ArtikelEdukasi::where('slug', $slug)
            ->where('publikasi', true)
            ->firstOrFail();
    }

    public function render()
    {
        // Artikel terkait (3 terbaru selain ini)
        $terkait = ArtikelEdukasi::where('publikasi', true)
            ->where('id', '!=', $this->artikel->id)
            ->where('kategori', $this->artikel->kategori)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.publik.baca-artikel', [
            'terkait' => $terkait
        ])->layout('components.layouts.app', ['title' => $this->artikel->judul]);
    }
}