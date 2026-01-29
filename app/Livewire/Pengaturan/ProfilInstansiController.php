<?php

namespace App\Livewire\Pengaturan;

use App\Models\ProfilInstansi;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Title('Pengaturan Instansi & Homepage')]
class ProfilInstansiController extends Component
{
    use WithFileUploads;

    // Data Instansi
    public $nama_instansi;
    public $alamat;
    public $telepon;
    public $email;
    public $visi;
    public $misi;

    // Data Homepage (Hero)
    public $hero_title;
    public $hero_subtitle;
    public $hero_image; // Upload baru
    public $hero_image_lama; // Path lama

    // Data Homepage (Sambutan)
    public $nama_kepala_puskesmas;
    public $sambutan_kepala;
    public $foto_kepala; // Upload baru
    public $foto_kepala_lama; // Path lama
    
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
            
            $this->hero_title = $profil->hero_title;
            $this->hero_subtitle = $profil->hero_subtitle;
            $this->hero_image_lama = $profil->hero_image;
            
            $this->nama_kepala_puskesmas = $profil->nama_kepala_puskesmas;
            $this->sambutan_kepala = $profil->sambutan_kepala;
            $this->foto_kepala_lama = $profil->foto_kepala;
        }
    }

    public function simpan()
    {
        $this->validate([
            'nama_instansi' => 'required|string|max:255',
            'email' => 'required|email',
            'hero_image' => 'nullable|image|max:2048', // Max 2MB
            'foto_kepala' => 'nullable|image|max:2048',
        ]);

        $profil = ProfilInstansi::first();
        if (!$profil) {
            $profil = new ProfilInstansi();
        }

        // Upload Hero Image
        if ($this->hero_image) {
            if ($this->hero_image_lama) {
                Storage::disk('public')->delete($this->hero_image_lama);
            }
            $profil->hero_image = $this->hero_image->store('cms', 'public');
        }

        // Upload Foto Kepala
        if ($this->foto_kepala) {
            if ($this->foto_kepala_lama) {
                Storage::disk('public')->delete($this->foto_kepala_lama);
            }
            $profil->foto_kepala = $this->foto_kepala->store('cms', 'public');
        }

        $profil->nama_instansi = $this->nama_instansi;
        $profil->alamat = $this->alamat;
        $profil->telepon = $this->telepon;
        $profil->email = $this->email;
        $profil->visi = $this->visi;
        $profil->misi = $this->misi;
        
        $profil->hero_title = $this->hero_title;
        $profil->hero_subtitle = $this->hero_subtitle;
        $profil->nama_kepala_puskesmas = $this->nama_kepala_puskesmas;
        $profil->sambutan_kepala = $this->sambutan_kepala;

        $profil->save();

        session()->flash('pesan', 'Pengaturan instansi & homepage berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.pengaturan.profil-instansi')
            ->layout('components.layouts.admin');
    }
}