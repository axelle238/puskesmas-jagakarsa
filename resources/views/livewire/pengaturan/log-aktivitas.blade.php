<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Audit Trail (Log Aktivitas)</h1>
        <p class="text-slate-500">Pantau seluruh aktivitas pengguna dalam sistem.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <input wire:model.live.debounce.300ms="cari" type="text" class="w-full md:w-1/3 rounded-lg border-slate-300 text-sm" placeholder="Cari aktivitas...">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Detail</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-900">
                            {{ $log->pengguna->nama_lengkap ?? 'System / Guest' }}
                            <span class="block text-xs text-slate-400 font-normal">{{ $log->pengguna->peran ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                {{ $log->action == 'LOGIN' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $log->action == 'CREATE' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $log->action == 'UPDATE' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $log->action == 'DELETE' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $log->description }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada aktivitas tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>