<?php

namespace App\Livewire\Publik;

use App\Models\Antrian;
use App\Models\JadwalDokter;
use App\Models\Pasien;
use App\Models\Poli;
use Carbon\Carbon;
use Livewire\Component;

class AmbilAntrian extends Component
{
    // State Steps: 1=Cek/Regis, 2=Pilih Poli, 3=Konfirmasi, 4=Sukses
    public $tahap = 1;

    // Data Input Pasien
    public $nik;
    public $nama_lengkap;
    public $no_hp;
    public $pasien_ditemukan = null;
    public $mode_registrasi_baru = false;

    // Data Input Antrian
    public $id_poli_dipilih;
    public $id_jadwal_dipilih;
    public $tanggal_kunjungan;
    
    // Data Output
    public $tiket_antrian;

    public function mount()
    {
        $this->tanggal_kunjungan = date('Y-m-d');
    }

    // --- TAHAP 1: IDENTIFIKASI PASIEN ---

    public function cekNik()
    {
        $this->validate([
            'nik' => 'required|numeric|digits:16'
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit.',
        ]);

        $pasien = Pasien::where('nik', $this->nik)->first();

        if ($pasien) {
            $this->pasien_ditemukan = $pasien;
            $this->tahap = 2; // Lanjut pilih poli
        } else {
            $this->pasien_ditemukan = null;
            $this->mode_registrasi_baru = true; // Buka form regis baru
        }
    }

    public function daftarBaru()
    {
        $this->validate([
            'nik' => 'required|numeric|digits:16|unique:pasien,nik',
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => 'required|numeric',
        ], [
            'nik.unique' => 'NIK sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.'
        ]);

        // Simpan Pasien Baru (Sederhana)
        $pasien = Pasien::create([
            'nik' => $this->nik,
            'nama_lengkap' => $this->nama_lengkap,
            'no_telepon' => $this->no_hp,
            'no_rekam_medis' => $this->generateNoRM(),
            'alamat_lengkap' => '-', // Default, dilengkapi di loket
            'tanggal_lahir' => '2000-01-01', // Default
            'jenis_kelamin' => 'L', // Default
        ]);

        $this->pasien_ditemukan = $pasien;
        $this->mode_registrasi_baru = false;
        $this->tahap = 2;
    }

    private function generateNoRM()
    {
        // Format: RM-YYYYMM-XXXX
        $prefix = 'RM-' . date('Ym') . '-';
        $last = Pasien::where('no_rekam_medis', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->no_rekam_medis, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // --- TAHAP 2: PILIH LAYANAN ---

    public function pilihJadwal($idJadwal)
    {
        $this->id_jadwal_dipilih = $idJadwal;
        $jadwal = JadwalDokter::find($idJadwal);
        $this->id_poli_dipilih = $jadwal->id_poli;
        
        $this->tahap = 3; // Lanjut konfirmasi
    }

    public function kembali()
    {
        if ($this->tahap > 1) {
            $this->tahap--;
        }
    }

    // --- TAHAP 3: PROSES ANTRIAN ---

    public function prosesAntrian()
    {
        // Validasi double booking hari ini
        $cek = Antrian::where('id_pasien', $this->pasien_ditemukan->id)
            ->where('tanggal_antrian', $this->tanggal_kunjungan)
            ->where('status', '!=', 'batal')
            ->exists();

        if ($cek) {
            session()->flash('error', 'Anda sudah memiliki antrian untuk hari ini.');
            return;
        }

        $jadwal = JadwalDokter::with('poli')->find($this->id_jadwal_dipilih);

        // Generate Nomor Antrian
        $count = Antrian::where('id_poli', $this->id_poli_dipilih)
            ->where('tanggal_antrian', $this->tanggal_kunjungan)
            ->count();
        
        $nomorUrut = $count + 1;
        $prefix = $jadwal->poli->kode_poli ?? 'A';
        $nomorAntrian = $prefix . '-' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        $antrian = Antrian::create([
            'id_pasien' => $this->pasien_ditemukan->id,
            'id_poli' => $this->id_poli_dipilih,
            'id_jadwal' => $this->id_jadwal_dipilih,
            'nomor_antrian' => $nomorAntrian,
            'tanggal_antrian' => $this->tanggal_kunjungan,
            'status' => 'menunggu',
            'waktu_checkin' => now(), // Auto checkin
        ]);

        $this->tiket_antrian = $antrian;
        $this->tahap = 4; // Sukses
    }

    public function render()
    {
        $jadwalTersedia = [];
        if ($this->tahap == 2) {
            // Set locale ID untuk Carbon agar nama hari sesuai (Senin, Selasa...)
            Carbon::setLocale('id');
            $hariIni = Carbon::now()->isoFormat('dddd');
            
            $jadwalTersedia = JadwalDokter::with(['dokter.pengguna', 'poli'])
                ->where('hari', $hariIni)
                ->get();
        }

        return view('livewire.publik.ambil-antrian', [
            'jadwalTersedia' => $jadwalTersedia
        ]);
    }
}