<?php

namespace App\Livewire\Medis;

use App\Models\Antrian;
use App\Models\Poli;
use App\Models\JadwalDokter;
use App\Services\WhatsappService; // Import Service
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Class AntrianPoli
 * Halaman kerja Dokter/Perawat untuk memantau antrian pasien hari ini.
 * Fitur: Panggil pasien (WA Notif), Mulai Periksa, Lewati.
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
            $pegawai = $user->pegawai;
            if ($pegawai) {
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
        $antrian = Antrian::with(['pasien', 'poli'])->find($id);
        if ($antrian && $antrian->status == 'menunggu') {
            $antrian->status = 'dipanggil';
            $antrian->save();
            $this->antrianDipanggil = $antrian;
            
            // Integrasi WhatsApp Notification
            if ($antrian->pasien && $antrian->pasien->no_telepon) {
                $pesan = "Halo {$antrian->pasien->nama_lengkap}, giliran Anda nomor {$antrian->nomor_antrian} telah dipanggil di {$antrian->poli->nama_poli}. Silakan masuk ke ruangan. Terima kasih.";
                WhatsappService::kirimPesan($antrian->pasien->no_telepon, $pesan);
            }
            
            // Dispatch event untuk frontend (suara bell dll)
            $this->dispatch('bunyikan-bel', nomor: $antrian->nomor_antrian);
        }
    }

    public function mulaiPeriksa($id)
    {
        $antrian = Antrian::find($id);
        if ($antrian) {
            $antrian->status = 'diperiksa';
            $antrian->save();
            
            return redirect()->route('medis.periksa', ['idAntrian' => $id]);
        }
    }

    public function lewatiAntrian($id)
    {
        $antrian = Antrian::find($id);
        if ($antrian) {
            $antrian->status = 'batal';
            $antrian->save();
        }
    }

    public function render()
    {
        $hariIni = Carbon::today();
        $daftarPoli = Poli::where('aktif', true)->get();

        $query = Antrian::with(['pasien', 'poli'])
            ->whereDate('tanggal_antrian', $hariIni)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->orderBy('id', 'asc');

        if ($this->filterPoli) {
            $query->where('id_poli', $this->filterPoli);
        }

        $antrianList = $query->get();
        $sisaAntrian = $antrianList->where('status', 'menunggu')->count();

        return view('livewire.medis.antrian-poli', [
            'antrianList' => $antrianList,
            'daftarPoli' => $daftarPoli,
            'sisaAntrian' => $sisaAntrian
        ])->layout('components.layouts.admin', ['title' => 'Antrian Poli']);
    }
}
