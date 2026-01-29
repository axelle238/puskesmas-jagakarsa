<?php

namespace App\Livewire\Publik;

use Livewire\Component;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\JadwalDokter;
use App\Models\Antrian;
use Carbon\Carbon;

class AmbilAntrian extends Component
{
    // State Langkah
    public $langkah = 1; // 1: Cek Data, 2: Pilih Poli, 3: Tiket

    // Form Data
    public $nik;
    public $no_rekam_medis;
    
    // Data Pasien Terpilih
    public $pasien;
    
    // Pilihan Layanan
    public $poli_id;
    public $jadwal_id;
    
    // Data Pendukung
    public $daftarPoli = [];
    public $daftarJadwal = [];
    
    // Hasil Tiket
    public $tiketAntrian;

    public function mount()
    {
        // Inisialisasi daftar poli
        $this->daftarPoli = Poli::all();
    }

    // Langkah 1: Cari Data Pasien
    public function cariPasien()
    {
        $this->validate([
            'nik' => 'required|numeric|digits:16',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit.',
        ]);

        $this->pasien = Pasien::where('nik', $this->nik)->first();

        if ($this->pasien) {
            $this->langkah = 2; // Lanjut pilih poli
        } else {
            // TODO: Arahkan ke form pendaftaran pasien baru jika belum ada
            session()->flash('error', 'Data pasien tidak ditemukan. Silakan daftar baru di loket atau hubungi petugas.');
        }
    }

    // Langkah 2: Update Pilihan Poli dan Load Jadwal
    public function updatedPoliId($value)
    {
        $hariIni = Carbon::now()->isoFormat('dddd'); // Senin, Selasa, dst
        
        $this->daftarJadwal = JadwalDokter::where('id_poli', $value)
                                ->where('hari', $hariIni)
                                ->where('aktif', true)
                                ->with('dokter.pengguna')
                                ->get();
    }

    // Langkah 2: Proses Ambil Antrian
    public function ambilAntrian()
    {
        $this->validate([
            'poli_id' => 'required',
            'jadwal_id' => 'required'
        ]);

        // Cek kuota / double booking (opsional, skip dulu untuk MVP)

        // Generate Nomor Antrian (Contoh: A-001)
        // Logika sederhana: Hitung antrian di poli tersebut hari ini + 1
        $jumlahAntrian = Antrian::where('id_poli', $this->poli_id)
                            ->whereDate('tanggal_antrian', Carbon::today())
                            ->count();
        
        $kodePoli = substr(Poli::find($this->poli_id)->nama_poli, 0, 1); // U untuk Umum, G untuk Gigi
        $nomorUrut = str_pad($jumlahAntrian + 1, 3, '0', STR_PAD_LEFT);
        $nomorAntrian = $kodePoli . '-' . $nomorUrut;

        // Simpan
        $antrian = Antrian::create([
            'id_pasien' => $this->pasien->id,
            'id_poli' => $this->poli_id,
            'id_jadwal' => $this->jadwal_id,
            'nomor_antrian' => $nomorAntrian,
            'tanggal_antrian' => Carbon::today(),
            'status' => 'menunggu',
            'waktu_checkin' => now()
        ]);

        $this->tiketAntrian = $antrian;
        $this->langkah = 3; // Selesai
    }

    public function render()
    {
        return view('livewire.publik.ambil-antrian')
            ->layout('components.layouts.public', ['title' => 'Ambil Antrian']);
    }
}
