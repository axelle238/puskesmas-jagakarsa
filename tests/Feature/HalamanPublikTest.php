<?php

namespace Tests\Feature;

use App\Models\ProfilInstansi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HalamanPublikTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function halaman_beranda_bisa_diakses()
    {
        // Setup data profil
        ProfilInstansi::create([
            'nama_instansi' => 'Puskesmas Test',
            'hero_title' => 'Selamat Datang di Test',
            'hero_subtitle' => 'Melayani dengan Sepenuh Hati',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // View uses strtoupper() for nama_instansi
        $response->assertSee('PUSKESMAS TEST'); 
        $response->assertSee('Selamat Datang di Test');
    }

    /** @test */
    public function halaman_jadwal_dokter_bisa_diakses()
    {
        $response = $this->get('/jadwal-dokter');
        $response->assertStatus(200);
        $response->assertSee('Jadwal Praktik Dokter');
    }

    /** @test */
    public function halaman_antrian_online_bisa_diakses()
    {
        $response = $this->get('/antrian-online');
        $response->assertStatus(200);
        $response->assertSee('Ambil Antrian Online');
    }
}