<?php

namespace App\Livewire\Medis;

use App\Models\Antrian;
use App\Models\JadwalDokter;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Title('Antrian Pemeriksaan')]
class AntrianPoli extends Component
{
    public function render()
    {
        $user = Auth::user();
        $query = Antrian::with(['pasien', 'poli', 'jadwal.dokter'])
                    ->whereDate('tanggal_antrian', Carbon::today())
                    ->whereIn('status', ['menunggu', 'dipanggil'])
                    ->orderBy('created_at', 'asc');

        // Jika login sebagai Dokter, filter antrian milik dia saja
        if ($user->peran === 'dokter') {
            $dokter = Pegawai::where('id_pengguna', $user->id)->first();
            if ($dokter) {
                $query->whereHas('jadwal', function($q) use ($dokter) {
                    $q->where('id_dokter', $dokter->id);
                });
            }
        }

        $antrians = $query->get();

        return view('livewire.medis.antrian-poli', [
            'antrians' => $antrians,
            'isDokter' => $user->peran === 'dokter'
        ])->layout('components.layouts.admin');
    }

    public function panggil($id)
    {
        $antrian = Antrian::find($id);
        if ($antrian) {
            $antrian->status = 'dipanggil';
            $antrian->save();
            // Di sistem real-time, ini akan memicu event WebSocket ke layar antrian
            session()->flash('pesan', 'Pasien ' . $antrian->pasien->nama_lengkap . ' dipanggil.');
        }
    }

    public function mulaiPeriksa($id)
    {
        $antrian = Antrian::find($id);
        $antrian->status = 'diperiksa';
        $antrian->save();
        
        return redirect()->route('medis.periksa', ['idAntrian' => $id]);
    }
}
