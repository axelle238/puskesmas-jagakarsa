<?php

namespace App\Livewire\Pengaturan;

use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profil extends Component
{
    public $nama_lengkap;
    public $email;
    public $no_telepon;
    public $alamat;
    
    public $sandi_lama;
    public $sandi_baru;
    public $konfirmasi_sandi;

    public function mount()
    {
        $user = auth()->user();
        $this->nama_lengkap = $user->nama_lengkap;
        $this->email = $user->email;
        $this->no_telepon = $user->no_telepon;
        $this->alamat = $user->alamat;
    }

    public function simpanProfil()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . auth()->id(),
        ]);

        $user = Pengguna::find(auth()->id());
        $user->update([
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'no_telepon' => $this->no_telepon,
            'alamat' => $this->alamat,
        ]);

        session()->flash('sukses_profil', 'Profil berhasil diperbarui.');
    }

    public function gantiSandi()
    {
        $this->validate([
            'sandi_lama' => 'required',
            'sandi_baru' => 'required|min:8|same:konfirmasi_sandi',
        ]);

        $user = Pengguna::find(auth()->id());

        if (!Hash::check($this->sandi_lama, $user->sandi)) {
            $this->addError('sandi_lama', 'Kata sandi lama salah.');
            return;
        }

        $user->update([
            'sandi' => Hash::make($this->sandi_baru)
        ]);

        $this->reset(['sandi_lama', 'sandi_baru', 'konfirmasi_sandi']);
        session()->flash('sukses_sandi', 'Kata sandi berhasil diubah.');
    }

    public function render()
    {
        return view('livewire.pengaturan.profil')->layout('components.layouts.admin', ['title' => 'Pengaturan Profil']);
    }
}