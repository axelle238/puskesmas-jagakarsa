<?php

namespace App\Livewire\Publik;

use App\Models\ArtikelEdukasi;
use Livewire\Component;
use Livewire\WithPagination;

class EdukasiKesehatan extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.publik.edukasi-kesehatan', [
            'artikels' => ArtikelEdukasi::where('publikasi', true)
                ->with('penulis')
                ->latest()
                ->paginate(9)
        ])->layout('components.layouts.public', ['title' => 'Edukasi Kesehatan']);
    }
}
