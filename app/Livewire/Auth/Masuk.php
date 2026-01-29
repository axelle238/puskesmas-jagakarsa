<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')] 
#[Title('Masuk - Sistem Pegawai')]
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

        if (Auth::attempt(['email' => $this->email, 'password' => $this->sandi], $this->ingat_saya)) {
            session()->regenerate();
            return redirect()->intended(route('dasbor'));
        }

        $this->addError('email', 'Kombinasi email dan kata sandi tidak cocok.');
    }

    public function render()
    {
        return view('livewire.auth.masuk');
    }
}
