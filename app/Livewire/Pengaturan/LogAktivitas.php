<?php

namespace App\Livewire\Pengaturan;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class LogAktivitas extends Component
{
    use WithPagination;

    public $cari = '';

    public function render()
    {
        $logs = ActivityLog::with('pengguna')
            ->where('description', 'like', '%' . $this->cari . '%')
            ->orWhereHas('pengguna', function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->cari . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.pengaturan.log-aktivitas', [
            'logs' => $logs
        ])->layout('components.layouts.admin', ['title' => 'Audit Trail']);
    }
}