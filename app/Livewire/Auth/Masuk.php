<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Masuk extends Component
{
    public $email;
    public $sandi;
    public $ingat_saya = false;

    protected $rules = [
        'email' => 'required|email',
        'sandi' => 'required',
    ];

    protected $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'sandi.required' => 'Kata sandi wajib diisi.',
    ];

    public function masuk()
    {
        $this->validate();

        // Auth::attempt secara default mencari key 'password' di array credentials
        // Namun model Pengguna sudah di-override getAuthPassword()-nya.
        // Kita tetap kirim key 'password' agar SessionGuard Laravel mengerti input mana yang plain text password.
        if (Auth::attempt(['email' => $this->email, 'password' => $this->sandi], $this->ingat_saya)) {
            
            session()->regenerate();

            // Redirect berdasarkan peran
            $peran = auth()->user()->peran;
            
            if ($peran === 'pasien') {
                return redirect()->intended(route('beranda'));
            }

            return redirect()->intended(route('dasbor'));
        }

        $this->addError('email', 'Kombinasi email dan kata sandi tidak cocok.');
    }

    public function render()
    {
        return view('livewire.auth.masuk')->layout('components.layouts.auth');
    }
}