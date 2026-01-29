<?php

namespace App\Livewire\Pengaturan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Pengaturan Profil')]
class Profil extends Component
{
    public $nama_lengkap;
    public $email;
    public $no_telepon;
    public $alamat;
    
    // Ganti Password
    public $sandi_lama;
    public $sandi_baru;
    public $konfirmasi_sandi;

    public function mount()
    {
        $user = Auth::user();
        $this->nama_lengkap = $user->nama_lengkap;
        $this->email = $user->email;
        $this->no_telepon = $user->no_telepon;
        $this->alamat = $user->alamat;
    }

    public function simpanProfil()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . Auth::id(),
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        Auth::user()->update([
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'no_telepon' => $this->no_telepon,
            'alamat' => $this->alamat,
        ]);

        session()->flash('pesan_profil', 'Profil berhasil diperbarui.');
    }

    public function gantiSandi()
    {
        $this->validate([
            'sandi_lama' => 'required',
            'sandi_baru' => 'required|min:6|same:konfirmasi_sandi',
        ]);

        if (!Hash::check($this->sandi_lama, Auth::user()->sandi)) {
            $this->addError('sandi_lama', 'Kata sandi lama salah.');
            return;
        }

        Auth::user()->update([
            'sandi' => Hash::make($this->sandi_baru)
        ]);

        $this->reset(['sandi_lama', 'sandi_baru', 'konfirmasi_sandi']);
        session()->flash('pesan_sandi', 'Kata sandi berhasil diubah.');
    }

    public function render()
    {
        return view('livewire.pengaturan.profil')
            ->layout('components.layouts.admin');
    }
}
