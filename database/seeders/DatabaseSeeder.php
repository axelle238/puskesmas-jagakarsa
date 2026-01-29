<?php

namespace Database\Seeders;

use App\Models\KlasterIlp;
use App\Models\Obat;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Poli;
use App\Models\TindakanMedis;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // 1. Buat Klaster ILP (Standar Kemenkes)
        $klaster1 = KlasterIlp::create(['nama_klaster' => 'Klaster 1: Manajemen', 'deskripsi_layanan' => 'Ketatausahaan dan Manajemen Puskesmas']);
        $klaster2 = KlasterIlp::create(['nama_klaster' => 'Klaster 2: Ibu dan Anak', 'deskripsi_layanan' => 'Kesehatan Ibu, Anak, dan Remaja']);
        $klaster3 = KlasterIlp::create(['nama_klaster' => 'Klaster 3: Dewasa dan Lansia', 'deskripsi_layanan' => 'Kesehatan Usia Produktif dan Lanjut Usia']);
        $klaster4 = KlasterIlp::create(['nama_klaster' => 'Klaster 4: Penanggulangan Penyakit', 'deskripsi_layanan' => 'Penyakit Menular dan KLB']);
        $klaster5 = KlasterIlp::create(['nama_klaster' => 'Lintas Klaster', 'deskripsi_layanan' => 'Laboratorium, Farmasi, dan Gawat Darurat']);

        // 2. Buat Poli / Unit Layanan Terhubung dengan Klaster
        Poli::create(['id_klaster' => $klaster3->id, 'nama_poli' => 'Poli Umum', 'deskripsi' => 'Pelayanan kesehatan umum dasar', 'lokasi_ruangan' => 'Lantai 1 - R.101']);
        Poli::create(['id_klaster' => $klaster3->id, 'nama_poli' => 'Poli Gigi & Mulut', 'deskripsi' => 'Kesehatan gigi dasar dan tindakan', 'lokasi_ruangan' => 'Lantai 1 - R.102']);
        Poli::create(['id_klaster' => $klaster2->id, 'nama_poli' => 'Poli KIA (Ibu & Anak)', 'deskripsi' => 'Pemeriksaan hamil dan balita', 'lokasi_ruangan' => 'Lantai 1 - R.103']);
        Poli::create(['id_klaster' => $klaster3->id, 'nama_poli' => 'Poli Lansia', 'deskripsi' => 'Pelayanan prioritas lansia', 'lokasi_ruangan' => 'Lantai 1 - R.104']);
        Poli::create(['id_klaster' => $klaster5->id, 'nama_poli' => 'IGD 24 Jam', 'deskripsi' => 'Penanganan gawat darurat', 'lokasi_ruangan' => 'Lantai Dasar']);
        
        // Poli Tambahan untuk ILP
        Poli::create(['id_klaster' => $klaster2->id, 'nama_poli' => 'MTBS / MTBM', 'deskripsi' => 'Manajemen Terpadu Balita Sakit', 'lokasi_ruangan' => 'Lantai 1 - R.105']);
        Poli::create(['id_klaster' => $klaster4->id, 'nama_poli' => 'Poli TB / Paru', 'deskripsi' => 'Penanganan Tuberkulosis', 'lokasi_ruangan' => 'Lantai 2 - R.201']);

        // 3. Buat Tindakan Medis Dasar
        $poliUmum = Poli::where('nama_poli', 'Poli Umum')->first();
        $poliGigi = Poli::where('nama_poli', 'Poli Gigi & Mulut')->first();

        TindakanMedis::create(['kode_tindakan' => 'TM-001', 'nama_tindakan' => 'Pemeriksaan Dokter Umum', 'id_poli' => $poliUmum->id, 'tarif' => 15000]);
        TindakanMedis::create(['kode_tindakan' => 'TM-002', 'nama_tindakan' => 'Jahit Luka Ringan (1-3)', 'id_poli' => $poliUmum->id, 'tarif' => 35000]);
        TindakanMedis::create(['kode_tindakan' => 'TM-003', 'nama_tindakan' => 'Cabut Gigi Susu', 'id_poli' => $poliGigi->id, 'tarif' => 50000]);
        TindakanMedis::create(['kode_tindakan' => 'TM-004', 'nama_tindakan' => 'Pembersihan Karang Gigi', 'id_poli' => $poliGigi->id, 'tarif' => 75000]);

        // 4. Buat Stok Obat Awal
        Obat::create([
            'kode_obat' => 'OBT-001', 'nama_obat' => 'Paracetamol 500mg', 'kategori' => 'Obat Bebas', 
            'satuan' => 'Tablet', 'stok_saat_ini' => 1000, 'stok_minimum' => 100, 
            'harga_satuan' => 500, 'tanggal_kedaluwarsa' => '2027-12-31'
        ]);
        Obat::create([
            'kode_obat' => 'OBT-002', 'nama_obat' => 'Amoxicillin 500mg', 'kategori' => 'Obat Keras', 
            'satuan' => 'Kapsul', 'stok_saat_ini' => 500, 'stok_minimum' => 50, 
            'harga_satuan' => 1000, 'tanggal_kedaluwarsa' => '2026-06-30'
        ]);
        Obat::create([
            'kode_obat' => 'OBT-003', 'nama_obat' => 'Vitamin C 50mg', 'kategori' => 'Obat Bebas', 
            'satuan' => 'Tablet', 'stok_saat_ini' => 2000, 'stok_minimum' => 200, 
            'harga_satuan' => 200, 'tanggal_kedaluwarsa' => '2028-01-01'
        ]);
        Obat::create([
            'kode_obat' => 'OBT-004', 'nama_obat' => 'OAT (Paket Obat TB)', 'kategori' => 'Obat Keras', 
            'satuan' => 'Paket', 'stok_saat_ini' => 50, 'stok_minimum' => 10, 
            'harga_satuan' => 0, 'tanggal_kedaluwarsa' => '2026-12-31'
        ]);

        // 5. Buat Akun Admin
        $admin = Pengguna::create([
            'nama_lengkap' => 'Administrator Sistem',
            'email' => 'admin@puskesmas.id',
            'sandi' => Hash::make('admin123'),
            'peran' => 'admin',
            'alamat' => 'Kantor IT'
        ]);
        Pegawai::create([
            'id_pengguna' => $admin->id,
            'nip' => '199001012024011001',
            'jabatan' => 'Kepala IT',
            'tanggal_masuk' => '2020-01-01'
        ]);

        // 6. Buat Akun Dokter
        $dokter = Pengguna::create([
            'nama_lengkap' => 'dr. Budi Santoso',
            'email' => 'dokter@puskesmas.id',
            'sandi' => Hash::make('dokter123'),
            'peran' => 'dokter',
            'alamat' => 'Jakarta Selatan'
        ]);
        Pegawai::create([
            'id_pengguna' => $dokter->id,
            'nip' => '198505052015021002',
            'str' => '1234567890',
            'sip' => 'SIP.123/456/2025',
            'jabatan' => 'Dokter Fungsional',
            'spesialisasi' => 'Umum',
            'tanggal_masuk' => '2015-02-01'
        ]);

        // 7. Buat Akun Apoteker
        $apoteker = Pengguna::create([
            'nama_lengkap' => 'Siti Aminah, S.Farm, Apt',
            'email' => 'farmasi@puskesmas.id',
            'sandi' => Hash::make('farmasi123'),
            'peran' => 'apoteker',
            'alamat' => 'Depok'
        ]);
        Pegawai::create([
            'id_pengguna' => $apoteker->id,
            'nip' => '199208172019032005',
            'str' => '9876543210',
            'jabatan' => 'Kepala Farmasi',
            'tanggal_masuk' => '2019-03-01'
        ]);
    }
}