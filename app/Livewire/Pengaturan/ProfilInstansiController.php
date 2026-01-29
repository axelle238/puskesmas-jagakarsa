<?php

namespace App\Livewire\Pengaturan;

use App\Models\ProfilInstansi;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;

#[Title('Pengaturan Instansi')]
class ProfilInstansiController extends Component
{
    use WithFileUploads;

    public $nama_instansi;
    public $alamat;
    public $telepon;
    public $email;
    public $visi;
    public $misi;
    
    public function mount()
    {
        $profil = ProfilInstansi::first();
        if ($profil) {
            $this->nama_instansi = $profil->nama_instansi;
            $this->alamat = $profil->alamat;
            $this->telepon = $profil->telepon;
            $this->email = $profil->email;
            $this->visi = $profil->visi;
            $this->misi = $profil->misi;
        }
    }

    public function simpan()
    {
        $this->validate([
            'nama_instansi' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $profil = ProfilInstansi::first();
        if (!$profil) {
            $profil = new ProfilInstansi();
        }

        $profil->nama_instansi = $this->nama_instansi;
        $profil->alamat = $this->alamat;
        $profil->telepon = $this->telepon;
        $profil->email = $this->email;
        $profil->visi = $this->visi;
        $profil->misi = $this->misi;
        $profil->save();

        session()->flash('pesan', 'Profil instansi berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.pengaturan.profil-instansi')
            ->layout('components.layouts.admin');
    }
}
