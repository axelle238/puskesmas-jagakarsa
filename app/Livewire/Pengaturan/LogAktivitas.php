<?php

namespace App\Livewire\Pengaturan;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class LogAktivitas extends Component
{
    use WithPagination;

    public $cari = '';
    public $filterAction = '';
    public $detailLog = null;
    public $modalOpen = false;

    public function showDetail($id)
    {
        $this->detailLog = ActivityLog::find($id);
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->detailLog = null;
    }

    public function render()
    {
        $query = ActivityLog::with('pengguna');

        if ($this->cari) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->cari . '%')
                  ->orWhere('target_model', 'like', '%' . $this->cari . '%')
                  ->orWhereHas('pengguna', function($subQ) {
                      $subQ->where('nama_lengkap', 'like', '%' . $this->cari . '%');
                  });
            });
        }

        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.pengaturan.log-aktivitas', [
            'logs' => $logs
        ])->layout('components.layouts.admin', ['title' => 'Audit Trail']);
    }
}
