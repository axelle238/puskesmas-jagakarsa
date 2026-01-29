<?php

namespace App\Livewire\Publik;

use App\Models\Fasilitas;
use Livewire\Component;

class FasilitasPublik extends Component
{
    public function render()
    {
        $fasilitas = Fasilitas::latest()->get();
        
        return view('livewire.publik.fasilitas-publik', [
            'fasilitas' => $fasilitas
        ])->layout('components.layouts.app', ['title' => 'Fasilitas Puskesmas']);
    }
}