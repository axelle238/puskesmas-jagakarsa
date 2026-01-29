<?php

namespace App\Livewire\Farmasi;

use App\Models\RekamMedis;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Title('Antrian Resep')]
class DaftarResep extends Component
{
    use WithPagination;

    public $filterStatus = 'menunggu'; // menunggu, disiapkan, selesai

    public function render()
    {
        // Ambil Rekam Medis yang punya resep dan tanggal hari ini
        $reseps = RekamMedis::whereHas('resepDetail', function ($query) {
                $query->where('status_pengambilan', $this->filterStatus);
            })
            ->whereDate('created_at', today())
            ->with(['pasien', 'dokter.pengguna', 'poli', 'resepDetail' => function($q) {
                $q->where('status_pengambilan', $this->filterStatus);
            }])
            ->latest()
            ->paginate(10);

        return view('livewire.farmasi.daftar-resep', [
            'reseps' => $reseps
        ])->layout('components.layouts.admin');
    }

    public function ubahStatus($resepId, $obatId, $statusBaru)
    {
        // Update status di pivot table
        // Kita perlu mencari rekam medis dan update pivotnya
        $rm = RekamMedis::find($resepId);
        if ($rm) {
            $rm->resepDetail()->updateExistingPivot($obatId, ['status_pengambilan' => $statusBaru]);
            
            // Jika status 'selesai', bisa tambahkan logika notifikasi ke pasien di sini
        }
    }

    public function prosesSemua($resepId, $statusBaru)
    {
        $rm = RekamMedis::find($resepId);
        if ($rm) {
            foreach ($rm->resepDetail as $obat) {
                // Hanya update yang statusnya sesuai filter saat ini agar tidak merubah yang sudah selesai
                if ($obat->pivot->status_pengambilan == $this->filterStatus) {
                    $rm->resepDetail()->updateExistingPivot($obat->id, ['status_pengambilan' => $statusBaru]);
                }
            }
            session()->flash('pesan', 'Status seluruh obat berhasil diperbarui.');
        }
    }
}
