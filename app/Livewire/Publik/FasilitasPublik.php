<?php

namespace App\Livewire\Publik;

use App\Models\Fasilitas;
use Livewire\Component;

class FasilitasPublik extends Component
{
    public function render()
    {
        return view('livewire.publik.fasilitas-publik', [
            'fasilitas' => Fasilitas::all()
        ])->layout('components.layouts.public', ['title' => 'Fasilitas Kami']);
    }
}
