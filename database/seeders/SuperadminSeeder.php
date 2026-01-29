<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Superadmin
        $user = Pengguna::create([
            'nama_lengkap' => 'Super Administrator',
            'email' => 'superadmin@puskesmas.id',
            'sandi' => Hash::make('superadmin123'), // Password kuat
            'peran' => 'superadmin',
            'no_telepon' => '080000000000',
            'alamat' => 'Server Room'
        ]);

        // 2. Buat Data Pegawai (Wajib ada agar tidak error saat relasi $user->pegawai diakses)
        Pegawai::create([
            'id_pengguna' => $user->id,
            'nip' => '99999999999', // NIP Khusus
            'nik' => '9999999999999999',
            'jabatan' => 'IT Superadmin',
            'status_kepegawaian' => 'PNS',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'pendidikan_terakhir' => 'S3',
            'tanggal_masuk' => now()
        ]);

        $this->command->info('Superadmin berhasil dibuat!');
        $this->command->info('Email: superadmin@puskesmas.id');
        $this->command->info('Sandi: superadmin123');
    }
}
