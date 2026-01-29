<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Audit Log Sistem</h1>
            <p class="text-gray-500 text-sm">Rekam jejak aktivitas pengguna untuk keamanan dan audit.</p>
        </div>
        
        <div class="flex gap-2">
            <select wire:model.live="filterAction" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Aksi</option>
                <option value="CREATE">CREATE</option>
                <option value="UPDATE">UPDATE</option>
                <option value="DELETE">DELETE</option>
                <option value="LOGIN">LOGIN</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3">Waktu</th>
                    <th class="px-6 py-3">Pengguna</th>
                    <th class="px-6 py-3">Aksi</th>
                    <th class="px-6 py-3">Modul</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-500 whitespace-nowrap">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td class="px-6 py-3 font-medium text-gray-800">
                        {{ $log->pengguna->nama_lengkap ?? 'Sistem' }}
                        <div class="text-xs text-gray-400">{{ $log->pengguna->peran ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-3">
                        @if($log->action == 'CREATE')
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">CREATE</span>
                        @elseif($log->action == 'UPDATE')
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">UPDATE</span>
                        @elseif($log->action == 'DELETE')
                            <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold">DELETE</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold">{{ $log->action }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $log->module }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $log->description }}</td>
                    <td class="px-6 py-3 text-gray-400 font-mono text-xs">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada aktivitas tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
