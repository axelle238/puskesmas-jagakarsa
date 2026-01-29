<?php

namespace App\Livewire\Medis;

use App\Models\Antrian;
use App\Models\Poli;
use App\Models\JadwalDokter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Class AntrianPoli
 * Halaman kerja Dokter/Perawat untuk memantau antrian pasien hari ini.
 * Fitur: Panggil pasien, Mulai Periksa, Lewati.
 */
class AntrianPoli extends Component
{
    public $filterPoli = null;
    public $antrianDipanggil = null;

    public function mount()
    {
        // Otomatis filter poli berdasarkan jadwal dokter yang login (jika dokter)
        $user = Auth::user();
        if ($user && $user->peran == 'dokter') {
            // Cari jadwal hari ini untuk dokter ini
            // Logic sederhana: Ambil poli pertama dari jadwal dokter ini
            $pegawai = $user->pegawai; // Relasi di model Pengguna
            if ($pegawai) {
                // Cari jadwal hari ini
                Carbon::setLocale('id');
                $hariIni = Carbon::now()->isoFormat('dddd');
                $jadwal = JadwalDokter::where('id_dokter', $pegawai->id)
                    ->where('hari', $hariIni)
                    ->first();
                
                if ($jadwal) {
                    $this->filterPoli = $jadwal->id_poli;
                }
            }
        }
    }

    public function panggilAntrian($id)
    {
        $antrian = Antrian::find($id);
        if ($antrian && $antrian->status == 'menunggu') {
            $antrian->status = 'dipanggil';
            $antrian->save();
            $this->antrianDipanggil = $antrian;
            
            // TODO: Integrasi dengan Text-to-Speech atau Bell di browser (Frontend side)
            $this->dispatch('bunyikan-bel', nomor: $antrian->nomor_antrian);
        }
    }

    public function mulaiPeriksa($id)
    {
        $antrian = Antrian::find($id);
        if ($antrian) {
            $antrian->status = 'diperiksa';
            $antrian->save();
            
            // Redirect ke halaman pemeriksaan (Rekam Medis)
            return redirect()->route('medis.periksa', ['idAntrian' => $id]);
        }
    }

    public function lewatiAntrian($id)
    {
        // Tandai tidak hadir atau batal
        $antrian = Antrian::find($id);
        if ($antrian) {
            $antrian->status = 'batal'; // Atau status khusus 'dilewati'
            $antrian->save();
        }
    }

    public function render()
    {
        $hariIni = Carbon::today();
        
        // Ambil daftar poli untuk filter dropdown
        $daftarPoli = Poli::where('aktif', true)->get();

        $query = Antrian::with(['pasien', 'poli'])
            ->whereDate('tanggal_antrian', $hariIni)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->orderBy('id', 'asc'); // Urutkan siapa cepat dia dapat

        if ($this->filterPoli) {
            $query->where('id_poli', $this->filterPoli);
        }

        $antrianList = $query->get();
        
        // Hitung sisa antrian
        $sisaAntrian = $antrianList->where('status', 'menunggu')->count();

        return view('livewire.medis.antrian-poli', [
            'antrianList' => $antrianList,
            'daftarPoli' => $daftarPoli,
            'sisaAntrian' => $sisaAntrian
        ])->layout('components.layouts.admin', ['title' => 'Antrian Poli']);
    }
}