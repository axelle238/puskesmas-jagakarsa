<?php

namespace App\Livewire\Publik;

use App\Models\JadwalDokter as ModelJadwal;
use App\Models\Poli;
use Livewire\Component;

class JadwalDokter extends Component
{
    public $filterPoli = '';
    public $filterHari = '';
    public $search = ''; // Pencarian nama dokter

    public function render()
    {
        $query = ModelJadwal::with(['dokter.pengguna', 'poli'])
            ->where('aktif', true);

        // Filter Poli
        if ($this->filterPoli) {
            $query->where('id_poli', $this->filterPoli);
        }

        // Filter Hari
        if ($this->filterHari) {
            $query->where('hari', $this->filterHari);
        }

        // Filter Pencarian Nama Dokter
        if ($this->search) {
            $query->whereHas('dokter.pengguna', function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%');
            });
        }

        $jadwal = $query->get();

        // Custom Sort Hari (Senin, Selasa, ...)
        $urutanHari = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 
            'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7
        ];

        $jadwal = $jadwal->sortBy(function ($item) use ($urutanHari) {
            $hariVal = $urutanHari[$item->hari] ?? 99;
            $jamVal = str_replace(':', '', $item->jam_mulai);
            return $hariVal . '-' . $jamVal;
        });

        $poliList = Poli::where('aktif', true)->get();

        return view('livewire.publik.jadwal-dokter', [
            'jadwal' => $jadwal,
            'poliList' => $poliList
        ])->layout('components.layouts.publik', ['title' => 'Jadwal Dokter']);
    }
}
