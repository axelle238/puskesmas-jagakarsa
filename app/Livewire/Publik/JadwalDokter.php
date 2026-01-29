<?php

namespace App\Livewire\Publik;

use App\Models\JadwalDokter as ModelJadwal;
use App\Models\Poli;
use Livewire\Component;

class JadwalDokter extends Component
{
    public $filterPoli = '';
    public $filterHari = '';

    public function render()
    {
        $query = ModelJadwal::with(['dokter.pengguna', 'poli'])
            ->where('aktif', true);

        if ($this->filterPoli) {
            $query->where('id_poli', $this->filterPoli);
        }

        if ($this->filterHari) {
            $query->where('hari', $this->filterHari);
        }

        // Urutkan berdasarkan hari (Senin, Selasa, ...) - perlu logika custom atau field urutan
        // Sederhananya kita group by hari di view atau biarkan apa adanya
        $jadwal = $query->get();

        $poliList = Poli::all();

        return view('livewire.publik.jadwal-dokter', [
            'jadwal' => $jadwal,
            'poliList' => $poliList
        ])->layout('components.layouts.publik', ['title' => 'Jadwal Dokter']);
    }
}
