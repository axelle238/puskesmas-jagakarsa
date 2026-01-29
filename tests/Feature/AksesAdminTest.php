<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\Pegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AksesAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function halaman_admin_redirect_ke_login_jika_belum_masuk()
    {
        $response = $this->get('/dasbor');
        $response->assertRedirect('/masuk');
    }

    /** @test */
    public function admin_bisa_akses_dasbor()
    {
        // Buat user admin
        $user = Pengguna::create([
            'nama_lengkap' => 'Admin Test',
            'email' => 'admin@test.com',
            'sandi' => Hash::make('password'),
            'peran' => 'admin'
        ]);
        
        // Buat pegawai linked (untuk role check di middleware jika ada)
        Pegawai::create([
            'id_pengguna' => $user->id,
            'nip' => '12345678',
            'jabatan' => 'Administrator'
        ]);

        $this->actingAs($user);

        $response = $this->get('/dasbor');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang, Admin Test');
    }

    /** @test */
    public function dokter_bisa_akses_halaman_medis()
    {
        $user = Pengguna::create([
            'nama_lengkap' => 'Dr. Test',
            'email' => 'dokter@test.com',
            'sandi' => Hash::make('password'),
            'peran' => 'dokter'
        ]);
        
        Pegawai::create([
            'id_pengguna' => $user->id,
            'nip' => '87654321',
            'jabatan' => 'Dokter Umum'
        ]);

        $this->actingAs($user);

        $response = $this->get('/medis/antrian');
        $response->assertStatus(200);
    }
}
