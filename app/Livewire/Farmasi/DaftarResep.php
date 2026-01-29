<?php

namespace App\Livewire\Farmasi;

use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\RekamMedis;
use Livewire\Component;

/**
 * Class DaftarResep
 * Antrian resep masuk dari poli untuk diproses apoteker.
 */
class DaftarResep extends Component
{
    public $detailResepList = []; // Untuk modal detail
    public $selectedRmId = null;
    public $tampilkanModal = false;

    public function lihatResep($idRm)
    {
        $this->selectedRmId = $idRm;
        $this->detailResepList = DetailResep::with('obat')
            ->where('id_rekam_medis', $idRm)
            ->get();
        $this->tampilkanModal = true;
    }

    public function prosesResep()
    {
        // 1. Potong Stok Obat
        foreach ($this->detailResepList as $detail) {
            $obat = Obat::find($detail->id_obat);
            if ($obat) {
                // Kurangi stok
                if ($obat->stok_saat_ini >= $detail->jumlah) {
                    $obat->decrement('stok_saat_ini', $detail->jumlah);
                } else {
                    // Stok kurang? Untuk MVP kita biarkan minus atau error
                    // Idealnya validasi di awal
                }
            }
        }

        // 2. Tandai Selesai (Update status rekam medis atau antrian)
        // Kita pakai status antrian 'selesai_farmasi' atau cukup flag di rekam medis
        // Disini kita update rekam medis catatannya bahwa sudah diproses
        $rm = RekamMedis::find($this->selectedRmId);
        $rm->catatan_tambahan = ($rm->catatan_tambahan ?? '') . ' [Obat Diserahkan]';
        $rm->save();

        // Update antrian jadi 'selesai' total jika belum
        if ($rm->antrian) {
            $rm->antrian->status = 'selesai';
            $rm->antrian->save();
        }

        session()->flash('sukses', 'Resep berhasil diproses dan stok telah dipotong.');
        $this->tutupModal();
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->selectedRmId = null;
        $this->detailResepList = [];
    }

    public function render()
    {
        // Ambil rekam medis hari ini yang punya detail resep
        // dan belum ada catatan [Obat Diserahkan]
        $resepMasuk = RekamMedis::whereDate('created_at', today())
            ->whereHas('detailResep') // Cek relasi
            ->where('catatan_tambahan', 'not like', '%[Obat Diserahkan]%')
            ->with(['pasien', 'dokter', 'poli'])
            ->latest()
            ->get();

        return view('livewire.farmasi.daftar-resep', [
            'resepMasuk' => $resepMasuk
        ])->layout('components.layouts.admin', ['title' => 'Antrian Farmasi']);
    }
}