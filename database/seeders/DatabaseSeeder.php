<?php

namespace Database\Seeders;

use App\Models\ArtikelEdukasi;
use App\Models\Fasilitas;
use App\Models\KlasterIlp;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Poli;
use App\Models\RekamMedis;
use App\Models\TindakanMedis;
use App\Models\Antrian;
use App\Models\JadwalDokter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Klaster ILP
        $klaster1 = KlasterIlp::create(['nama_klaster' => 'Klaster 1: Manajemen', 'deskripsi_layanan' => 'Ketatausahaan']);
        $klaster2 = KlasterIlp::create(['nama_klaster' => 'Klaster 2: Ibu dan Anak', 'deskripsi_layanan' => 'Kesehatan Ibu, Anak']);
        $klaster3 = KlasterIlp::create(['nama_klaster' => 'Klaster 3: Dewasa dan Lansia', 'deskripsi_layanan' => 'Kesehatan Usia Produktif']);
        $klaster4 = KlasterIlp::create(['nama_klaster' => 'Klaster 4: Penanggulangan Penyakit', 'deskripsi_layanan' => 'Penyakit Menular']);
        $klaster5 = KlasterIlp::create(['nama_klaster' => 'Lintas Klaster', 'deskripsi_layanan' => 'Lab & Farmasi']);

        // 2. Poli
        $poliUmum = Poli::create(['id_klaster' => $klaster3->id, 'kode_poli' => 'P-UMUM', 'nama_poli' => 'Poli Umum', 'deskripsi' => 'Layanan umum', 'lokasi_ruangan' => 'Lantai 1']);
        $poliGigi = Poli::create(['id_klaster' => $klaster3->id, 'kode_poli' => 'P-GIGI', 'nama_poli' => 'Poli Gigi', 'deskripsi' => 'Layanan gigi', 'lokasi_ruangan' => 'Lantai 1']);
        Poli::create(['id_klaster' => $klaster2->id, 'kode_poli' => 'P-KIA', 'nama_poli' => 'Poli KIA', 'deskripsi' => 'Ibu & Anak', 'lokasi_ruangan' => 'Lantai 1']);

        // 3. Tindakan
        TindakanMedis::create(['kode_tindakan' => 'TM-001', 'nama_tindakan' => 'Pemeriksaan Dokter', 'id_poli' => $poliUmum->id, 'tarif' => 15000]);
        TindakanMedis::create(['kode_tindakan' => 'TM-002', 'nama_tindakan' => 'Cabut Gigi', 'id_poli' => $poliGigi->id, 'tarif' => 50000]);

        // 4. Obat
        Obat::create(['kode_obat' => 'OBT-001', 'nama_obat' => 'Paracetamol 500mg', 'kategori' => 'Obat Bebas', 'satuan' => 'Tablet', 'stok_saat_ini' => 1000, 'stok_minimum' => 100, 'harga_satuan' => 500, 'tanggal_kedaluwarsa' => '2026-12-31']);
        Obat::create(['kode_obat' => 'OBT-002', 'nama_obat' => 'Amoxicillin 500mg', 'kategori' => 'Obat Keras', 'satuan' => 'Kapsul', 'stok_saat_ini' => 500, 'stok_minimum' => 50, 'harga_satuan' => 1000, 'tanggal_kedaluwarsa' => '2026-06-30']);

        // 5. Akun Pegawai
        $admin = Pengguna::create(['nama_lengkap' => 'Admin Sistem', 'email' => 'admin@puskesmas.id', 'sandi' => Hash::make('admin123'), 'peran' => 'admin']);
        Pegawai::create(['id_pengguna' => $admin->id, 'nip' => '1001', 'jabatan' => 'IT']);

        $dokter = Pengguna::create(['nama_lengkap' => 'dr. Budi Santoso', 'email' => 'dokter@puskesmas.id', 'sandi' => Hash::make('dokter123'), 'peran' => 'dokter']);
        $pegawaiDokter = Pegawai::create(['id_pengguna' => $dokter->id, 'nip' => '2001', 'sip' => 'SIP-123', 'jabatan' => 'Dokter Umum']);

        $apoteker = Pengguna::create(['nama_lengkap' => 'Siti Apt', 'email' => 'farmasi@puskesmas.id', 'sandi' => Hash::make('farmasi123'), 'peran' => 'apoteker']);
        Pegawai::create(['id_pengguna' => $apoteker->id, 'nip' => '3001', 'jabatan' => 'Apoteker']);

        // 6. Jadwal Dokter
        JadwalDokter::create([
            'id_dokter' => $pegawaiDokter->id, 'id_poli' => $poliUmum->id, 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00', 'kuota_pasien' => 20
        ]);

        // 7. Fasilitas & Artikel
        Fasilitas::create(['nama_fasilitas' => 'Ruang Tunggu AC', 'deskripsi' => 'Nyaman dan sejuk']);
        ArtikelEdukasi::create(['judul' => 'Cegah DBD', 'slug' => 'cegah-dbd', 'ringkasan' => '3M Plus', 'konten' => 'Lakukan 3M Plus...', 'kategori' => 'Umum', 'id_penulis' => $dokter->id]);

        // --- DATA DUMMY PASIEN & KUNJUNGAN ---
        
        $pasien1 = Pasien::create([
            'no_rekam_medis' => 'RM-202401-001', 'nik' => '1234567890123456', 'nama_lengkap' => 'Ahmad Junaedi',
            'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '1990-05-15', 'jenis_kelamin' => 'L', 'alamat_lengkap' => 'Jl. Jagakarsa No. 1'
        ]);

        $pasien2 = Pasien::create([
            'no_rekam_medis' => 'RM-202401-002', 'nik' => '6543210987654321', 'nama_lengkap' => 'Siti Maemunah',
            'tempat_lahir' => 'Depok', 'tanggal_lahir' => '1985-10-20', 'jenis_kelamin' => 'P', 'alamat_lengkap' => 'Jl. Kebagusan No. 5'
        ]);

        // Kunjungan Hari Ini (Antrian)
        Antrian::create([
            'id_pasien' => $pasien1->id, 'id_poli' => $poliUmum->id, 'id_jadwal' => 1, 'nomor_antrian' => 'U-001',
            'tanggal_antrian' => Carbon::today(), 'status' => 'menunggu', 'waktu_checkin' => now()
        ]);

        // Kunjungan Masa Lalu (Rekam Medis) - Untuk Grafik
        RekamMedis::create([
            'id_pasien' => $pasien2->id, 'id_dokter' => $pegawaiDokter->id, 'id_poli' => $poliUmum->id,
            'keluhan_utama' => 'Demam', 'subjektif' => 'Demam 3 hari', 'objektif' => 'Suhu 38C',
            'asesmen' => 'Febris', 'diagnosis_kode' => 'R50.9', 'plan' => 'Istirahat',
            'created_at' => Carbon::yesterday()
        ]);
    }
}
