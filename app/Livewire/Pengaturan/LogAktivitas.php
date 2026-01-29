<?php

namespace App\Livewire\Pengaturan;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Audit Log Aktivitas')]
class LogAktivitas extends Component
{
    use WithPagination;

    public $filterUser = '';
    public $filterAction = '';

    public function render()
    {
        $logs = ActivityLog::with('pengguna')
            ->when($this->filterAction, function($q) {
                $q->where('action', $this->filterAction);
            })
            ->latest()
            ->paginate(20);

        return view('livewire.pengaturan.log-aktivitas', [
            'logs' => $logs
        ])->layout('components.layouts.admin');
    }
}
