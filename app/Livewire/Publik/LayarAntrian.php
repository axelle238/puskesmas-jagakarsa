<?php

namespace App\Livewire\Publik;

use App\Models\Antrian;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('components.layouts.public')]
#[Title('Layar Antrian')]
class LayarAntrian extends Component
{
    public function render()
    {
        // Ambil antrian yang sedang dipanggil (Status: dipanggil)
        // Diurutkan berdasarkan waktu update terakhir (yang baru saja dipanggil)
        $sedangDipanggil = Antrian::with(['poli', 'jadwal.dokter'])
            ->whereDate('tanggal_antrian', Carbon::today())
            ->where('status', 'dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();

        // Ambil daftar antrian per poli untuk list di samping/bawah
        $antrianPerPoli = Antrian::selectRaw('id_poli, max(nomor_antrian) as nomor_terakhir')
            ->whereDate('tanggal_antrian', Carbon::today())
            ->whereIn('status', ['dipanggil', 'diperiksa', 'selesai']) // Ambil yang sudah jalan
            ->groupBy('id_poli')
            ->with('poli')
            ->get();

        return view('livewire.publik.layar-antrian', [
            'sedangDipanggil' => $sedangDipanggil,
            'antrianPerPoli' => $antrianPerPoli
        ]);
    }
}
