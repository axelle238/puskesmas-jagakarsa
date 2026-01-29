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
        return view('livewire.publik.baca-artikel')
            ->layout('components.layouts.public', ['title' => $this->artikel->judul]);
    }
}
