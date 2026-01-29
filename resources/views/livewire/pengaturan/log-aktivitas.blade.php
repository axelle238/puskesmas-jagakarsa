<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Audit Trail (Jejak Audit)</h1>
        <p class="text-slate-500">Rekam jejak digital seluruh aktivitas sistem untuk keamanan dan transparansi.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4 justify-between">
            <div class="flex gap-2 w-full md:w-auto">
                <select wire:model.live="filterAction" class="rounded-lg border-slate-300 text-sm">
                    <option value="">Semua Aksi</option>
                    <option value="LOGIN">LOGIN</option>
                    <option value="CREATE">CREATE</option>
                    <option value="UPDATE">UPDATE</option>
                    <option value="DELETE">DELETE</option>
                </select>
                <input wire:model.live.debounce.300ms="cari" type="text" class="w-full md:w-64 rounded-lg border-slate-300 text-sm" placeholder="Cari user, deskripsi...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Aktor</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono whitespace-nowrap">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-900">{{ $log->pengguna->nama_lengkap ?? 'System' }}</div>
                            <div class="text-xs text-slate-400">{{ $log->ip_address }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'LOGIN' => 'bg-green-100 text-green-800',
                                    'CREATE' => 'bg-blue-100 text-blue-800',
                                    'UPDATE' => 'bg-yellow-100 text-yellow-800',
                                    'DELETE' => 'bg-red-100 text-red-800',
                                ];
                                $color = $colors[$log->action] ?? 'bg-slate-100 text-slate-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $color }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $log->description }}
                            @if($log->target_model)
                                <div class="text-xs text-slate-400 mt-0.5 font-mono">{{ class_basename($log->target_model) }} #{{ $log->target_id }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($log->properties)
                                <button wire:click="showDetail({{ $log->id }})" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    Lihat Data
                                </button>
                            @else
                                <span class="text-xs text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada data log yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- MODAL DETAIL -->
    @if($modalOpen && $detailLog)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-slate-900 mb-4" id="modal-title">
                        Detail Perubahan Data
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- OLD DATA -->
                        <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                            <h4 class="font-bold text-red-800 text-xs uppercase mb-2">Sebelum (Old)</h4>
                            <pre class="text-xs font-mono text-red-700 whitespace-pre-wrap">{{ json_encode($detailLog->properties['old'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                        </div>

                        <!-- NEW DATA -->
                        <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-100">
                            <h4 class="font-bold text-emerald-800 text-xs uppercase mb-2">Sesudah (New)</h4>
                            <pre class="text-xs font-mono text-emerald-700 whitespace-pre-wrap">{{ json_encode($detailLog->properties['new'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 flex justify-between">
                        <span>IP: {{ $detailLog->ip_address }}</span>
                        <span>User Agent: {{ Str::limit($detailLog->user_agent, 50) }}</span>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closeModal" class="w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
