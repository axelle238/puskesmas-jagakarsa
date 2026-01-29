<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Pusat Keamanan & IT</h1>
        <p class="text-slate-500">Kontrol akses, pemantauan ancaman, dan pemulihan bencana.</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
        @foreach(['dashboard' => 'Ringkasan', 'ip' => 'Whitelist IP', 'logs' => 'Log Keamanan', 'backup' => 'Backup Data', 'settings' => 'Konfigurasi'] as $key => $label)
            <button wire:click="$set('activeTab', '{{ $key }}')" 
                class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $activeTab === $key ? 'bg-slate-800 text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- TAB: DASHBOARD -->
    @if($activeTab === 'dashboard')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-red-50 p-6 rounded-xl border border-red-100">
            <h3 class="text-red-800 font-bold text-sm uppercase">Total Serangan Terblokir</h3>
            <p class="text-3xl font-black text-red-600 mt-2">{{ $stats['total_serangan'] }}</p>
            <p class="text-xs text-red-500 mt-1">Percobaan brute force</p>
        </div>
        <div class="bg-orange-50 p-6 rounded-xl border border-orange-100">
            <h3 class="text-orange-800 font-bold text-sm uppercase">Login Gagal (Hari Ini)</h3>
            <p class="text-3xl font-black text-orange-600 mt-2">{{ $stats['login_gagal_hari_ini'] }}</p>
            <p class="text-xs text-orange-500 mt-1">Potensi ancaman akun</p>
        </div>
        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
            <h3 class="text-blue-800 font-bold text-sm uppercase">Backup Terakhir</h3>
            <p class="text-xl font-bold text-blue-600 mt-2">
                {{ $stats['backup_terakhir'] ? $stats['backup_terakhir']->diffForHumans() : 'Belum Ada' }}
            </p>
            <p class="text-xs text-blue-500 mt-1">Status integritas data</p>
        </div>
    </div>
    @endif

    <!-- TAB: WHITELIST IP -->
    @if($activeTab === 'ip')
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold text-slate-900">Daftar IP Diizinkan</h3>
                <p class="text-sm text-slate-500">Hanya IP ini yang bisa mengakses halaman login Admin jika fitur aktif.</p>
            </div>
            <button wire:click="tambahIp" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Tambah IP</button>
        </div>

        @if(session('sukses_ip'))
            <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm">{{ session('sukses_ip') }}</div>
        @endif

        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">IP Address</th>
                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Keterangan</th>
                    <th class="px-4 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($whitelist as $ip)
                <tr>
                    <td class="px-4 py-3 font-mono text-sm">{{ $ip->ip_address }}</td>
                    <td class="px-4 py-3 text-sm">{{ $ip->keterangan }}</td>
                    <td class="px-4 py-3 text-right">
                        <button wire:click="hapusIp({{ $ip->id }})" class="text-red-600 hover:underline text-xs font-bold">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- TAB: LOG KEAMANAN -->
    @if($activeTab === 'logs')
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <input type="text" wire:model.live.debounce.300ms="cariLog" placeholder="Cari IP atau Event..." class="w-full md:w-64 rounded-lg border-slate-300 text-sm">
        </div>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Waktu</th>
                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Event</th>
                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">IP Address</th>
                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Target</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-2 text-xs text-slate-500 font-mono">{{ $log->created_at->format('d/m H:i:s') }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $log->event == 'Login Sukses' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $log->event }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm font-mono">{{ $log->ip_address }}</td>
                    <td class="px-4 py-2 text-sm text-slate-600">{{ $log->target_email ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada log keamanan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
    @endif

    <!-- TAB: BACKUP -->
    @if($activeTab === 'backup')
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold text-slate-900">Cadangan Data (Backup)</h3>
                <p class="text-sm text-slate-500">Unduh atau pulihkan data sistem.</p>
            </div>
            <button wire:click="triggerBackup" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Jalankan Backup Sekarang
            </button>
        </div>

        @if(session('sukses_backup'))
            <div class="mb-4 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm">{{ session('sukses_backup') }}</div>
        @endif

        <div class="space-y-3">
            @foreach($backups as $backup)
            <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="bg-slate-200 p-2 rounded">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm text-slate-900">{{ $backup->nama_file }}</p>
                        <p class="text-xs text-slate-500">{{ $backup->created_at->format('d M Y H:i') }} • {{ $backup->ukuran_file }} • Oleh: {{ $backup->pembuat->nama_lengkap ?? 'System' }}</p>
                    </div>
                </div>
                <button class="text-blue-600 font-bold text-sm hover:underline">Unduh</button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- TAB: KONFIGURASI -->
    @if($activeTab === 'settings')
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-slate-900 mb-6">Konfigurasi Keamanan Global</h3>
        
        @if(session('sukses_settings'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sukses_settings') }}</div>
        @endif

        <form wire:submit="simpanPengaturan" class="space-y-6">
            <div class="flex items-center justify-between p-4 border rounded-lg bg-slate-50">
                <div>
                    <label class="block font-bold text-slate-800">Batasi Akses Login Admin</label>
                    <p class="text-xs text-slate-500">Hanya izinkan login dari daftar Whitelist IP.</p>
                </div>
                <input type="checkbox" wire:model="batasi_ip_login_admin" class="w-5 h-5 text-emerald-600 rounded">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Maksimal Percobaan Login</label>
                    <input type="number" wire:model="maksimal_percobaan_login" class="w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Durasi Blokir (Menit)</label>
                    <input type="number" wire:model="durasi_blokir_menit" class="w-full rounded-lg border-slate-300">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model="wajib_ganti_password_berkala" class="rounded text-emerald-600">
                <label class="text-sm text-slate-700">Wajibkan pengguna mengganti password setiap 90 hari</label>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-slate-900 text-white px-6 py-2 rounded-lg font-bold hover:bg-slate-800">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- MODAL IP -->
    @if($modalIp)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900 bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg mb-4">Tambah/Edit IP Address</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1">IP Address</label>
                    <input type="text" wire:model="ip_address" class="w-full rounded-lg border-slate-300 font-mono" placeholder="192.168.1.100">
                    @error('ip_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Keterangan Perangkat</label>
                    <input type="text" wire:model="keterangan_ip" class="w-full rounded-lg border-slate-300" placeholder="PC Admin 1">
                    @error('keterangan_ip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button wire:click="$set('modalIp', false)" class="px-4 py-2 text-slate-600 font-bold">Batal</button>
                <button wire:click="simpanIp" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold">Simpan</button>
            </div>
        </div>
    </div>
    @endif
</div>
