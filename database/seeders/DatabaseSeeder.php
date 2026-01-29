<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Antrian;
use App\Models\ArtikelEdukasi;
use App\Models\DetailResep;
use App\Models\DetailTagihan;
use App\Models\Fasilitas;
use App\Models\JadwalDokter;
use App\Models\KlasterIlp;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Poli;
use App\Models\Presensi;
use App\Models\ProfilInstansi;
use App\Models\RekamMedis;
use App\Models\Tagihan;
use App\Models\TindakanMedis;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Profil Instansi
        ProfilInstansi::create([
            'nama_instansi' => 'Puskesmas Kecamatan Jagakarsa',
            'alamat' => 'Jl. Moh. Kahfi 1 No. 1, Jagakarsa, Jakarta Selatan',
            'telepon' => '(021) 786-1234',
            'email' => 'info@puskesmas-jagakarsa.go.id',
            'visi' => 'Menjadi Puskesmas Terbaik di DKI Jakarta dengan Pelayanan Paripurna',
            'misi' => '1. Memberikan pelayanan kesehatan berkualitas. 2. Meningkatkan pemberdayaan masyarakat.',
            'hero_title' => 'Layanan Kesehatan Modern & Terintegrasi',
            'hero_subtitle' => 'Melayani dengan Hati, Menjangkau Semua Lapisan Masyarakat.',
            'nama_kepala_puskesmas' => 'dr. Pratama',
            'sambutan_kepala' => 'Selamat datang di website resmi Puskesmas Jagakarsa. Kami berkomitmen memberikan pelayanan terbaik bagi Anda.'
        ]);

        // 1. Klaster & Poli
        $klaster1 = KlasterIlp::create(['nama_klaster' => 'Klaster 1: Manajemen', 'deskripsi_layanan' => 'Ketatausahaan']);
        $klaster2 = KlasterIlp::create(['nama_klaster' => 'Klaster 2: Ibu dan Anak', 'deskripsi_layanan' => 'Kesehatan Ibu, Anak, dan Remaja']);
        $klaster3 = KlasterIlp::create(['nama_klaster' => 'Klaster 3: Dewasa dan Lansia', 'deskripsi_layanan' => 'Kesehatan Usia Produktif dan Lansia']);
        $klaster4 = KlasterIlp::create(['nama_klaster' => 'Klaster 4: Penanggulangan Penyakit', 'deskripsi_layanan' => 'Penyakit Menular']);
        $klaster5 = KlasterIlp::create(['nama_klaster' => 'Lintas Klaster', 'deskripsi_layanan' => 'Layanan Penunjang']);

        $poliKia = Poli::create(['id_klaster' => $klaster2->id, 'kode_poli' => 'P-KIA', 'nama_poli' => 'Poli KIA & KB', 'deskripsi' => 'Ibu & Anak', 'lokasi_ruangan' => 'Lantai 1']);
        $poliGigi = Poli::create(['id_klaster' => $klaster2->id, 'kode_poli' => 'P-GIGI', 'nama_poli' => 'Poli Gigi', 'deskripsi' => 'Kesehatan Gigi', 'lokasi_ruangan' => 'Lantai 2']);
        $poliUmum = Poli::create(['id_klaster' => $klaster3->id, 'kode_poli' => 'P-UMUM', 'nama_poli' => 'Poli Umum', 'deskripsi' => 'Pemeriksaan Umum', 'lokasi_ruangan' => 'Lantai 2']);
        $poliLansia = Poli::create(['id_klaster' => $klaster3->id, 'kode_poli' => 'P-LANSIA', 'nama_poli' => 'Poli Lansia', 'deskripsi' => 'Geriatri', 'lokasi_ruangan' => 'Lantai 1']);
        $poliIgd = Poli::create(['id_klaster' => $klaster5->id, 'kode_poli' => 'P-IGD', 'nama_poli' => 'IGD 24 Jam', 'deskripsi' => 'Gawat Darurat', 'lokasi_ruangan' => 'Lobby']);

        $polis = [$poliKia, $poliGigi, $poliUmum, $poliLansia, $poliIgd];

        // 2. Pengguna & Pegawai
        $createUser = function ($nama, $role, $nip, $jabatan) {
            $user = Pengguna::create([
                'nama_lengkap' => $nama,
                'email' => strtolower(str_replace([' ', '.', 'dr.'], '', $nama)) . '@puskesmas.id',
                'sandi' => Hash::make('password'),
                'peran' => $role,
                'no_telepon' => '0812' . rand(10000000, 99999999),
                'alamat' => 'Jakarta'
            ]);
            $peg = Pegawai::create(['id_pengguna' => $user->id, 'nip' => $nip, 'jabatan' => $jabatan]);
            return $peg;
        };

        $admin = $createUser('Admin Sistem', 'admin', '19900101', 'IT Staff');
        $drBudi = $createUser('dr. Budi', 'dokter', '19850101', 'Dokter Umum');
        $drSiti = $createUser('drg. Siti', 'dokter', '19860101', 'Dokter Gigi');
        $drRina = $createUser('dr. Rina', 'dokter', '19870101', 'Dokter Umum');
        $bidan = $createUser('Bidan Ani', 'perawat', '19950101', 'Bidan');
        $kasir = $createUser('Joko Kasir', 'kasir', '19980101', 'Kasir');
        
        $dokters = [$drBudi, $drSiti, $drRina];

        // 3. Jadwal Dokter
        foreach ($dokters as $dr) {
            foreach ($polis as $poli) {
                if ($dr->id == $drSiti->id && $poli->id != $poliGigi->id) continue;
                if ($dr->id != $drSiti->id && $poli->id == $poliGigi->id) continue;
                
                JadwalDokter::create([
                    'id_dokter' => $dr->id, 'id_poli' => $poli->id, 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00', 'kuota_pasien' => 20, 'aktif' => true
                ]);
            }
        }

        // 4. Obat
        $obats = [];
        for ($i = 1; $i <= 10; $i++) {
            $obats[] = Obat::create([
                'kode_obat' => 'OBT-00' . $i,
                'nama_obat' => 'Obat Generik ' . $i,
                'kategori' => 'Obat Bebas',
                'satuan' => 'Tablet',
                'stok_saat_ini' => rand(50, 5000),
                'stok_minimum' => 100,
                'harga_satuan' => rand(500, 5000),
                'tanggal_kedaluwarsa' => '2026-12-31'
            ]);
        }

        // 5. Tindakan
        TindakanMedis::create(['kode_tindakan' => 'T01', 'nama_tindakan' => 'Konsultasi', 'id_poli' => $poliUmum->id, 'tarif' => 15000]);
        TindakanMedis::create(['kode_tindakan' => 'T02', 'nama_tindakan' => 'Cabut Gigi', 'id_poli' => $poliGigi->id, 'tarif' => 100000]);

        // 6. Pasien (Bulk)
        $pasiens = [];
        for ($i = 1; $i <= 20; $i++) {
            $pasiens[] = Pasien::create([
                'no_rekam_medis' => 'RM-' . sprintf('%04d', $i),
                'nik' => '3174' . rand(100000000000, 999999999999),
                'nama_lengkap' => 'Pasien Dummy ' . $i,
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat_lengkap' => 'Jl. Jagakarsa No. ' . $i,
                'no_telepon' => '0812' . rand(10000000, 99999999),
                'no_bpjs' => $i % 3 == 0 ? '000' . rand(10000000, 99999999) : null
            ]);
        }

        // 7. Kunjungan / Antrian & Rekam Medis (Bulk - 30 Hari Terakhir)
        $diagnosaCommon = [
            ['J00', 'Nasopharyngitis acute (Common Cold)'],
            ['R50', 'Fever of other and unknown origin'],
            ['K29', 'Gastritis and duodenitis'],
            ['I10', 'Essential (primary) hypertension'],
            ['E11', 'Type 2 diabetes mellitus'],
            ['A09', 'Infectious gastroenteritis and colitis'],
            ['M79', 'Other soft tissue disorders (Myalgia)'],
            ['R51', 'Headache'],
            ['J06', 'Acute upper respiratory infections'],
            ['L20', 'Atopic dermatitis']
        ];

        for ($d = 30; $d >= 0; $d--) {
            $date = Carbon::today()->subDays($d);
            $dailyCount = rand(5, 15); // 5-15 patients per day

            for ($k = 0; $k < $dailyCount; $k++) {
                $pasien = $pasiens[array_rand($pasiens)];
                $poli = $polis[array_rand($polis)];
                $dokter = $dokters[array_rand($dokters)]; // Simplify mapping
                $diag = $diagnosaCommon[array_rand($diagnosaCommon)];

                $status = 'selesai';
                if ($d == 0) { // Hari ini
                    $status = rand(0, 1) ? 'selesai' : 'menunggu';
                }

                $antrian = Antrian::create([
                    'id_pasien' => $pasien->id,
                    'id_poli' => $poli->id,
                    'id_jadwal' => 1, // Dummy schedule
                    'nomor_antrian' => $poli->kode_poli . '-' . ($k + 1),
                    'tanggal_antrian' => $date,
                    'status' => $status,
                    'waktu_checkin' => $date->copy()->setHour(rand(7, 11))->setMinute(rand(0, 59)),
                    'waktu_selesai' => $status == 'selesai' ? $date->copy()->setHour(rand(12, 14)) : null
                ]);

                if ($status == 'selesai') {
                    // Create RM
                    $rm = RekamMedis::create([
                        'id_pasien' => $pasien->id,
                        'id_dokter' => $dokter->id,
                        'id_poli' => $poli->id,
                        'id_antrian' => $antrian->id,
                        'keluhan_utama' => 'Keluhan dummy...',
                        'subjektif' => 'S: Pasien mengeluh...',
                        'objektif' => 'O: TD 120/80...',
                        'asesmen' => $diag[1],
                        'diagnosis_kode' => $diag[0],
                        'plan' => 'P: Istirahat',
                        'tindakan' => 'Konsultasi',
                        'resep_obat' => 'Resep...',
                        'created_at' => $date->copy()->setHour(10)
                    ]);

                    // Tagihan
                    Tagihan::create([
                        'no_tagihan' => 'INV-' . $date->format('Ymd') . '-' . $k,
                        'id_rekam_medis' => $rm->id,
                        'id_kasir' => $kasir->id,
                        'total_biaya' => 50000,
                        'jumlah_bayar' => 50000,
                        'status_bayar' => 'lunas',
                        'metode_bayar' => 'tunai',
                        'created_at' => $date->copy()->setHour(11)
                    ]);
                }
            }
        }

        // 8. Presensi Hari Ini (Untuk Dashboard)
        $pegawaiList = Pegawai::all();
        foreach ($pegawaiList as $p) {
            // Randomly attend
            if (rand(0, 10) > 2) { // 80% attendance
                Presensi::create([
                    'id_pegawai' => $p->id,
                    'tanggal' => Carbon::today(),
                    'jam_masuk' => Carbon::now()->subHours(rand(1, 4)),
                    'status' => 'hadir'
                ]);
            }
        }
    }
}
