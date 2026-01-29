<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Antrian;
use App\Models\ArtikelEdukasi;
use App\Models\DetailResep;
use App\Models\DetailTagihan;
use App\Models\Fasilitas;
use App\Models\HasilLab;
use App\Models\JadwalDokter;
use App\Models\KlasterIlp;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\PermintaanLab;
use App\Models\Poli;
use App\Models\ProfilInstansi;
use App\Models\RekamMedis;
use App\Models\SuratKeterangan;
use App\Models\Tagihan;
use App\Models\TindakanMedis;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Profil Instansi (Data Awal)
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

        // 1. Klaster ILP (Integrasi Layanan Primer)
        $klaster1 = KlasterIlp::create(['nama_klaster' => 'Klaster 1: Manajemen', 'deskripsi_layanan' => 'Ketatausahaan dan Manajemen Puskesmas']);
        $klaster2 = KlasterIlp::create(['nama_klaster' => 'Klaster 2: Ibu dan Anak', 'deskripsi_layanan' => 'Kesehatan Ibu, Anak, dan Remaja']);
        $klaster3 = KlasterIlp::create(['nama_klaster' => 'Klaster 3: Dewasa dan Lansia', 'deskripsi_layanan' => 'Kesehatan Usia Produktif dan Lansia']);
        $klaster4 = KlasterIlp::create(['nama_klaster' => 'Klaster 4: Penanggulangan Penyakit', 'deskripsi_layanan' => 'Penyakit Menular dan Tidak Menular']);
        $klaster5 = KlasterIlp::create(['nama_klaster' => 'Lintas Klaster', 'deskripsi_layanan' => 'Layanan Penunjang (Lab, Farmasi, Gawat Darurat)']);

        // 2. Poli / Unit Layanan
        // Klaster 2
        $poliKia = Poli::create(['id_klaster' => $klaster2->id, 'kode_poli' => 'P-KIA', 'nama_poli' => 'Poli KIA & KB', 'deskripsi' => 'Kesehatan Ibu dan Anak', 'lokasi_ruangan' => 'Lantai 1, Sayap Kiri']);
        $poliMtbs = Poli::create(['id_klaster' => $klaster2->id, 'kode_poli' => 'P-MTBS', 'nama_poli' => 'Poli MTBS', 'deskripsi' => 'Manajemen Terpadu Balita Sakit', 'lokasi_ruangan' => 'Lantai 1, Sayap Kiri']);
        $poliGigi = Poli::create(['id_klaster' => $klaster2->id, 'kode_poli' => 'P-GIGI', 'nama_poli' => 'Poli Gigi & Mulut', 'deskripsi' => 'Kesehatan Gigi Dasar', 'lokasi_ruangan' => 'Lantai 2']);

        // Klaster 3
        $poliUmum = Poli::create(['id_klaster' => $klaster3->id, 'kode_poli' => 'P-UMUM', 'nama_poli' => 'Poli Umum', 'deskripsi' => 'Pemeriksaan Kesehatan Umum', 'lokasi_ruangan' => 'Lantai 2']);
        $poliLansia = Poli::create(['id_klaster' => $klaster3->id, 'kode_poli' => 'P-LANSIA', 'nama_poli' => 'Poli Lansia', 'deskripsi' => 'Kesehatan Lanjut Usia', 'lokasi_ruangan' => 'Lantai 1, Akses Mudah']);
        $poliJiwa = Poli::create(['id_klaster' => $klaster3->id, 'kode_poli' => 'P-JIWA', 'nama_poli' => 'Poli Jiwa', 'deskripsi' => 'Konseling Kesehatan Jiwa', 'lokasi_ruangan' => 'Lantai 3']);

        // Klaster 4
        $poliTb = Poli::create(['id_klaster' => $klaster4->id, 'kode_poli' => 'P-TB', 'nama_poli' => 'Poli TB Paru', 'deskripsi' => 'Pengobatan Tuberkulosis', 'lokasi_ruangan' => 'Area Terpisah']);
        
        // Klaster 5 (Penunjang)
        $poliLab = Poli::create(['id_klaster' => $klaster5->id, 'kode_poli' => 'P-LAB', 'nama_poli' => 'Laboratorium', 'deskripsi' => 'Pemeriksaan Sampel', 'lokasi_ruangan' => 'Lantai 1']);
        $poliIgd = Poli::create(['id_klaster' => $klaster5->id, 'kode_poli' => 'P-IGD', 'nama_poli' => 'IGD 24 Jam', 'deskripsi' => 'Gawat Darurat', 'lokasi_ruangan' => 'Lantai Dasar Depan']);

        // 3. Tindakan Medis (Master Tarif)
        $tindakan = [
            ['kode' => 'TM-001', 'nama' => 'Konsultasi Dokter Umum', 'poli' => $poliUmum->id, 'tarif' => 15000],
            ['kode' => 'TM-002', 'nama' => 'Pemeriksaan Surat Sehat', 'poli' => $poliUmum->id, 'tarif' => 25000],
            ['kode' => 'TM-003', 'nama' => 'Pencabutan Gigi Susu', 'poli' => $poliGigi->id, 'tarif' => 75000],
            ['kode' => 'TM-004', 'nama' => 'Pencabutan Gigi Tetap', 'poli' => $poliGigi->id, 'tarif' => 150000],
            ['kode' => 'TM-005', 'nama' => 'Pemeriksaan Kehamilan (ANC)', 'poli' => $poliKia->id, 'tarif' => 30000],
            ['kode' => 'TM-006', 'nama' => 'Imunisasi Dasar', 'poli' => $poliKia->id, 'tarif' => 0], // Subsidi
            ['kode' => 'TM-007', 'nama' => 'Cek Gula Darah Sewaktu', 'poli' => $poliUmum->id, 'tarif' => 20000],
            ['kode' => 'TM-008', 'nama' => 'Cek Kolesterol', 'poli' => $poliUmum->id, 'tarif' => 25000],
            ['kode' => 'TM-009', 'nama' => 'Nebulizer', 'poli' => $poliIgd->id, 'tarif' => 50000],
            ['kode' => 'TM-010', 'nama' => 'Rawat Luka Ringan', 'poli' => $poliIgd->id, 'tarif' => 35000],
        ];

        foreach ($tindakan as $t) {
            TindakanMedis::create([
                'kode_tindakan' => $t['kode'],
                'nama_tindakan' => $t['nama'],
                'id_poli' => $t['poli'],
                'tarif' => $t['tarif']
            ]);
        }

        // 4. Obat (Stok Farmasi)
        $daftarObat = [
            ['kode' => 'OBT-001', 'nama' => 'Paracetamol 500mg', 'kat' => 'Obat Bebas', 'sat' => 'Tablet', 'stok' => 5000, 'min' => 500, 'harga' => 500, 'exp' => '2027-12-31'],
            ['kode' => 'OBT-002', 'nama' => 'Amoxicillin 500mg', 'kat' => 'Obat Keras', 'sat' => 'Kapsul', 'stok' => 2000, 'min' => 200, 'harga' => 1000, 'exp' => '2026-06-30'],
            ['kode' => 'OBT-003', 'nama' => 'Antasida Doen', 'kat' => 'Obat Bebas', 'sat' => 'Tablet', 'stok' => 3000, 'min' => 300, 'harga' => 300, 'exp' => '2027-01-01'],
            ['kode' => 'OBT-004', 'nama' => 'CTM 4mg', 'kat' => 'Obat Bebas Terbatas', 'sat' => 'Tablet', 'stok' => 4000, 'min' => 400, 'harga' => 200, 'exp' => '2026-12-31'],
            ['kode' => 'OBT-005', 'nama' => 'Vitamin C 50mg', 'kat' => 'Suplemen', 'sat' => 'Tablet', 'stok' => 10000, 'min' => 1000, 'harga' => 100, 'exp' => '2028-01-01'],
            ['kode' => 'OBT-006', 'nama' => 'Amlodipine 5mg', 'kat' => 'Obat Keras', 'sat' => 'Tablet', 'stok' => 1500, 'min' => 150, 'harga' => 1500, 'exp' => '2026-05-20'],
            ['kode' => 'OBT-007', 'nama' => 'Metformin 500mg', 'kat' => 'Obat Keras', 'sat' => 'Tablet', 'stok' => 1500, 'min' => 150, 'harga' => 1200, 'exp' => '2026-08-15'],
            ['kode' => 'OBT-008', 'nama' => 'OBH Sirup', 'kat' => 'Obat Bebas', 'sat' => 'Botol', 'stok' => 500, 'min' => 50, 'harga' => 8000, 'exp' => '2025-12-31'],
            ['kode' => 'OBT-009', 'nama' => 'Betadine 30ml', 'kat' => 'Alat Kesehatan', 'sat' => 'Botol', 'stok' => 200, 'min' => 20, 'harga' => 15000, 'exp' => '2028-12-31'],
            ['kode' => 'OBT-010', 'nama' => 'Kapas Steril 25g', 'kat' => 'Alat Kesehatan', 'sat' => 'Bungkus', 'stok' => 300, 'min' => 30, 'harga' => 5000, 'exp' => '2030-01-01'],
        ];

        foreach ($daftarObat as $o) {
            Obat::create([
                'kode_obat' => $o['kode'],
                'nama_obat' => $o['nama'],
                'kategori' => $o['kat'],
                'satuan' => $o['sat'],
                'stok_saat_ini' => $o['stok'],
                'stok_minimum' => $o['min'],
                'harga_satuan' => $o['harga'],
                'tanggal_kedaluwarsa' => $o['exp']
            ]);
        }

        // 5. Pengguna & Pegawai (SDM)
        // Helper function for creating user & employee
        $createUser = function ($nama, $email, $role, $nip, $jabatan, $sip = null) {
            $user = Pengguna::create([
                'nama_lengkap' => $nama,
                'email' => $email,
                'sandi' => Hash::make('admin123'), // Default password
                'peran' => $role,
                'no_telepon' => '08123456789',
                'alamat' => 'Jakarta Selatan'
            ]);
            Pegawai::create([
                'id_pengguna' => $user->id,
                'nip' => $nip,
                'sip' => $sip,
                'jabatan' => $jabatan
            ]);
            return $user; // Return user for linking
        };

        $admin = $createUser('Administrator Sistem', 'admin@puskesmas.id', 'admin', '199001012020011001', 'Kepala IT');
        $kapus = $createUser('dr. Pratama', 'kapus@puskesmas.id', 'kapus', '198005052010011001', 'Kepala Puskesmas', 'SIP.123.456');
        
        // Dokter
        $drBudi = $createUser('dr. Budi Santoso', 'budi@puskesmas.id', 'dokter', '198503102015031002', 'Dokter Umum', 'SIP.111.222');
        $drSiti = $createUser('drg. Siti Aminah', 'siti@puskesmas.id', 'dokter', '198807122016042001', 'Dokter Gigi', 'SIP.333.444');
        $drRina = $createUser('dr. Rina Wijaya', 'rina@puskesmas.id', 'dokter', '199211202019052003', 'Dokter Umum', 'SIP.555.666');

        // Nakes Lain
        $perawat1 = $createUser('Ners. Agus', 'agus@puskesmas.id', 'perawat', '199501012020011002', 'Perawat Poli Umum');
        $bidan1 = $createUser('Bidan Dwi', 'dwi@puskesmas.id', 'perawat', '199602022020012003', 'Bidan Poli KIA', 'SIB.777.888'); // Role perawat/bidan disatukan atau dipisah logicnya
        
        $apoteker1 = $createUser('Apt. Rudi', 'rudi@puskesmas.id', 'apoteker', '199303032018011004', 'Kepala Farmasi', 'SIPA.999.000');
        $analis1 = $createUser('Lisa Amd.AK', 'lisa@puskesmas.id', 'analis', '199404042019012005', 'Analis Laboratorium');
        
        // Admin Staff
        $pendaftaran1 = $createUser('Dewi Front Office', 'daftar@puskesmas.id', 'pendaftaran', '199805052021012006', 'Petugas Pendaftaran');
        $kasir1 = $createUser('Joko Kasir', 'kasir@puskesmas.id', 'kasir', '199706062021011007', 'Petugas Kasir');

        // 6. Jadwal Dokter
        $jadwals = [
            ['dokter' => $drBudi, 'poli' => $poliUmum, 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00'],
            ['dokter' => $drBudi, 'poli' => $poliUmum, 'hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00'],
            ['dokter' => $drBudi, 'poli' => $poliUmum, 'hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
            
            ['dokter' => $drRina, 'poli' => $poliUmum, 'hari' => 'Selasa', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00'],
            ['dokter' => $drRina, 'poli' => $poliLansia, 'hari' => 'Kamis', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00'],
            
            ['dokter' => $drSiti, 'poli' => $poliGigi, 'hari' => 'Senin', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00'],
            ['dokter' => $drSiti, 'poli' => $poliGigi, 'hari' => 'Rabu', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00'],
            ['dokter' => $drSiti, 'poli' => $poliGigi, 'hari' => 'Kamis', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00'],
        ];

        foreach ($jadwals as $j) {
            JadwalDokter::create([
                'id_dokter' => $j['dokter']->pegawai->id,
                'id_poli' => $j['poli']->id,
                'hari' => $j['hari'],
                'jam_mulai' => $j['jam_mulai'],
                'jam_selesai' => $j['jam_selesai'],
                'kuota_pasien' => 20,
                'aktif' => true
            ]);
        }

        // 7. Fasilitas & Artikel
        Fasilitas::create(['nama_fasilitas' => 'Ruang Tunggu Nyaman', 'deskripsi' => 'Dilengkapi AC dan TV Edukasi', 'foto' => null]);
        Fasilitas::create(['nama_fasilitas' => 'Taman Bermain Anak', 'deskripsi' => 'Area bermain aman untuk anak-anak', 'foto' => null]);
        Fasilitas::create(['nama_fasilitas' => 'Ambulans 24 Jam', 'deskripsi' => 'Siap siaga untuk kegawatdaruratan', 'foto' => null]);

        ArtikelEdukasi::create(['judul' => 'Pentingnya Imunisasi Dasar Lengkap', 'slug' => 'imunisasi-dasar', 'ringkasan' => 'Lindungi buah hati Anda dengan imunisasi.', 'konten' => 'Imunisasi adalah cara terbaik mencegah penyakit berbahaya...', 'kategori' => 'Ibu & Anak', 'id_penulis' => $drBudi->id]);
        ArtikelEdukasi::create(['judul' => 'Cegah Diabetes Sejak Dini', 'slug' => 'cegah-diabetes', 'ringkasan' => 'Tips pola hidup sehat hindari gula berlebih.', 'konten' => 'Diabetes Melitus dapat dicegah dengan...', 'kategori' => 'Umum', 'id_penulis' => $drRina->id]);
        ArtikelEdukasi::create(['judul' => 'Cara Menyikat Gigi yang Benar', 'slug' => 'sikat-gigi-benar', 'ringkasan' => 'Teknik sikat gigi untuk senyum cemerlang.', 'konten' => 'Sikat gigi minimal 2 kali sehari...', 'kategori' => 'Gigi', 'id_penulis' => $drSiti->id]);

        // 8. Pasien Dummy
        $pasien1 = Pasien::create([
            'no_rekam_medis' => 'RM-202401-0001', 'nik' => '3174090101900001', 'nama_lengkap' => 'Bapak Budi', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '1990-01-01', 'jenis_kelamin' => 'L', 'alamat_lengkap' => 'Jl. Jagakarsa 1', 'no_telepon' => '0811111111', 'no_bpjs' => '00012345678'
        ]);
        $pasien2 = Pasien::create([
            'no_rekam_medis' => 'RM-202401-0002', 'nik' => '3174090202950002', 'nama_lengkap' => 'Ibu Ani', 'tempat_lahir' => 'Depok', 'tanggal_lahir' => '1995-02-02', 'jenis_kelamin' => 'P', 'alamat_lengkap' => 'Jl. Kebagusan 2', 'no_telepon' => '0822222222' // Umum
        ]);
        $pasien3 = Pasien::create([
            'no_rekam_medis' => 'RM-202401-0003', 'nik' => '3174090303800003', 'nama_lengkap' => 'Kakek Supri', 'tempat_lahir' => 'Solo', 'tanggal_lahir' => '1950-03-03', 'jenis_kelamin' => 'L', 'alamat_lengkap' => 'Jl. Ciganjur 3', 'no_telepon' => '0833333333', 'no_bpjs' => '00098765432'
        ]);

        // 9. Simulasi Kunjungan (Antrian, RM, Resep, Lab, Tagihan)
        
        // Kasus 1: Selesai (Pasien 1, Poli Umum, Dr. Budi, Ada Resep & Tagihan Lunas) - Kemarin
        $antrian1 = Antrian::create([
            'id_pasien' => $pasien1->id, 'id_poli' => $poliUmum->id, 'id_jadwal' => 1, 'nomor_antrian' => 'U-001',
            'tanggal_antrian' => Carbon::yesterday(), 'status' => 'selesai', 'waktu_checkin' => Carbon::yesterday()->setHour(8)->setMinute(0), 'waktu_selesai' => Carbon::yesterday()->setHour(9)->setMinute(0)
        ]);
        
        $rm1 = RekamMedis::create([
            'id_pasien' => $pasien1->id, 'id_dokter' => $drBudi->pegawai->id, 'id_poli' => $poliUmum->id, 'id_antrian' => $antrian1->id,
            'keluhan_utama' => 'Pusing dan lemas', 'subjektif' => 'Pasien mengeluh pusing sejak 2 hari lalu', 'objektif' => 'TD: 110/70, Nadi: 80', 'asesmen' => 'Cephalgia (Sakit Kepala)', 'diagnosis_kode' => 'R51', 'plan' => 'Istirahat cukup', 'tindakan' => 'Konsultasi', 'resep_obat' => 'Lihat Detail',
            'created_at' => Carbon::yesterday()->setHour(8)->setMinute(30)
        ]);

        DetailResep::create(['id_rekam_medis' => $rm1->id, 'id_obat' => 1, 'jumlah' => 10, 'dosis' => '3x1', 'harga_satuan_saat_ini' => 500]); // Paracetamol
        DetailResep::create(['id_rekam_medis' => $rm1->id, 'id_obat' => 5, 'jumlah' => 10, 'dosis' => '1x1', 'harga_satuan_saat_ini' => 100]); // Vit C

        // Tagihan
        $tagihan1 = Tagihan::create([
            'no_tagihan' => 'INV-'.Carbon::yesterday()->format('Ymd').'-001', 'id_rekam_medis' => $rm1->id, 'id_kasir' => $kasir1->pegawai->id,
            'total_biaya' => 6000, 'jumlah_bayar' => 6000, 'status_bayar' => 'gratis', 'metode_bayar' => 'bpjs', 'created_at' => Carbon::yesterday()
        ]);
        DetailTagihan::create(['id_tagihan' => $tagihan1->id, 'item' => 'Jasa Dokter', 'kategori' => 'Tindakan', 'jumlah' => 1, 'harga_satuan' => 0, 'subtotal' => 0]); // BPJS free

        // Kasus 2: Hari Ini - Sedang Diperiksa (Pasien 2, Poli Gigi, Dr. Siti)
        $antrian2 = Antrian::create([
            'id_pasien' => $pasien2->id, 'id_poli' => $poliGigi->id, 'id_jadwal' => 6, 'nomor_antrian' => 'G-001',
            'tanggal_antrian' => Carbon::today(), 'status' => 'dipanggil', 'waktu_checkin' => Carbon::now()->subMinutes(30)
        ]);

        // Kasus 3: Hari Ini - Menunggu (Pasien 3, Poli Lansia, Dr. Rina)
        $antrian3 = Antrian::create([
            'id_pasien' => $pasien3->id, 'id_poli' => $poliLansia->id, 'id_jadwal' => 5, 'nomor_antrian' => 'L-001',
            'tanggal_antrian' => Carbon::today(), 'status' => 'menunggu', 'waktu_checkin' => Carbon::now()->subMinutes(10)
        ]);

        // Log Aktivitas Dummy
        ActivityLog::create(['id_pengguna' => $pendaftaran1->id, 'action' => 'CREATE', 'description' => 'Mendaftarkan pasien baru RM-202401-0003', 'ip_address' => '127.0.0.1']);
        ActivityLog::create(['id_pengguna' => $drBudi->id, 'action' => 'CREATE', 'description' => 'Membuat rekam medis pasien RM-202401-0001', 'ip_address' => '127.0.0.1']);

    }
}