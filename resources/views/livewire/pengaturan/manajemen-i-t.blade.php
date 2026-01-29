<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen IT & Keamanan Siber</h1>
            <p class="text-slate-500">Pusat kontrol infrastruktur digital dan kebijakan keamanan sistem.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                Sistem Aman
            </span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200 mb-8">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
            <button wire:click="$set('activeTab', 'dashboard')" class="{{ $activeTab === 'dashboard' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Dashboard Keamanan
            </button>
            <button wire:click="$set('activeTab', 'ip')" class="{{ $activeTab === 'ip' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Kontrol Akses (IP)
            </button>
            <button wire:click="$set('activeTab', 'settings')" class="{{ $activeTab === 'settings' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Kebijakan Sistem
            </button>
            <button wire:click="$set('activeTab', 'logs')" class="{{ $activeTab === 'logs' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Log Keamanan
            </button>
            <button wire:click="$set('activeTab', 'backup')" class="{{ $activeTab === 'backup' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Backup & Recovery
            </button>
        </nav>
    </div>

    <!-- CONTENT: DASHBOARD -->
    @if($activeTab === 'dashboard')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
        <!-- Stat Card 1 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full z-0"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Ancaman Diblokir</p>
                <h3 class="text-3xl font-black text-slate-900">{{ $stats['total_serangan'] }}</h3>
                <p class="text-xs text-red-500 mt-2 font-medium">Brute Force / Suspicious IP</p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-50 rounded-full z-0"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Login Gagal Hari Ini</p>
                <h3 class="text-3xl font-black text-slate-900">{{ $stats['login_gagal_hari_ini'] }}</h3>
                <p class="text-xs text-yellow-600 mt-2 font-medium">Perlu perhatian</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full z-0"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Status Backup</p>
                <h3 class="text-lg font-black text-slate-900 truncate">
                    @if($stats['backup_terakhir'])
                        {{ $stats['backup_terakhir']->diffForHumans() }}
                    @else
                        Belum pernah
                    @endif
                </h3>
                <p class="text-xs text-blue-600 mt-2 font-medium">Penyimpanan Aman</p>
            </div>
        </div>

        <!-- Security Status -->
        <div class="col-span-1 md:col-span-3 bg-slate-900 rounded-xl p-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-900 to-slate-900 opacity-50"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-xl font-bold mb-2">Sistem Berjalan Normal</h3>
                    <p class="text-slate-400 max-w-xl">Tidak ada aktivitas mencurigakan yang terdeteksi dalam 24 jam terakhir. Firewall aktif dan seluruh endpoint terlindungi.</p>
                </div>
                <button wire:click="$set('activeTab', 'logs')" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 rounded-lg font-bold text-sm transition-colors shadow-lg shadow-emerald-900/50">
                    Periksa Log Detail
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- CONTENT: IP WHITELIST -->
    @if($activeTab === 'ip')
    <div class="space-y-6 animate-fade-in">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Daftar Putih IP (Whitelist)</h3>
            <button wire:click="tambahIp" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold shadow-sm transition-colors">
                + Tambah IP
            </button>
        </div>

        @if(session('sukses_ip'))
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200 text-sm font-bold">
                {{ session('sukses_ip') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Alamat IP</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($whitelist as $ip)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-mono text-sm text-slate-800">{{ $ip->ip_address }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $ip->keterangan }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $ip->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $ip->aktif ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="editIp({{ $ip->id }})" class="text-blue-600 hover:text-blue-800 font-bold text-xs mr-3">Edit</button>
                            <button wire:click="hapusIp({{ $ip->id }})" class="text-red-600 hover:text-red-800 font-bold text-xs" onclick="confirm('Hapus IP ini?') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada IP terdaftar. Akses terbuka untuk publik (berisiko).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- CONTENT: SETTINGS -->
    @if($activeTab === 'settings')
    <div class="max-w-3xl animate-fade-in">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-lg font-bold text-slate-900 mb-6">Kebijakan Keamanan Global</h3>
            
            @if(session('sukses_settings'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200 text-sm font-bold">
                    {{ session('sukses_settings') }}
                </div>
            @endif

            <form wire:submit="simpanPengaturan" class="space-y-6">
                <!-- IP Restriction -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Batasi Login Admin (IP Whitelist)</h4>
                        <p class="text-xs text-slate-500 mt-1">Hanya izinkan login admin dari IP yang terdaftar.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="batasi_ip_login_admin" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Password Rotation -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Wajib Ganti Password Berkala</h4>
                        <p class="text-xs text-slate-500 mt-1">Paksa pengguna mengganti password setiap 90 hari.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="wajib_ganti_password_berkala" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Login Limits -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Maksimal Percobaan Login Gagal</label>
                        <input type="number" wire:model="maksimal_percobaan_login" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        <p class="text-xs text-slate-500 mt-1">Akun akan diblokir sementara setelah ini.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Durasi Blokir (Menit)</label>
                        <input type="number" wire:model="durasi_blokir_menit" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        <p class="text-xs text-slate-500 mt-1">Lama waktu akun dikunci.</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-bold text-sm shadow-lg transition-transform transform hover:scale-[1.02]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- CONTENT: LOGS -->
    @if($activeTab === 'logs')
    <div class="animate-fade-in">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <input wire:model.live.debounce.300ms="cariLog" type="text" class="w-full md:w-1/3 rounded-lg border-slate-300 text-sm" placeholder="Cari IP, Event, atau User Agent...">
            </div>
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Target</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-6 py-4">
                            @if($log->event == 'Login Gagal')
                                <span class="text-red-600 font-bold text-xs uppercase">{{ $log->event }}</span>
                            @else
                                <span class="text-slate-700 font-bold text-xs uppercase">{{ $log->event }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $log->ip_address }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $log->target_email ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada log keamanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t border-slate-200">{{ $logs->links() }}</div>
        </div>
    </div>
    @endif

    <!-- CONTENT: BACKUP -->
    @if($activeTab === 'backup')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Backup Manual</h3>
                <p class="text-sm text-slate-500 mb-6">Buat cadangan database dan file sistem saat ini secara instan. Proses ini mungkin memakan waktu beberapa menit.</p>
                
                @if(session('sukses_backup'))
                    <div class="mb-6 p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm border border-emerald-200">
                        {{ session('sukses_backup') }}
                    </div>
                @endif

                <button wire:click="triggerBackup" wire:loading.attr="disabled" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm shadow-lg flex justify-center items-center gap-2 disabled:opacity-50">
                    <span wire:loading.remove wire:target="triggerBackup">Mulai Backup Sekarang</span>
                    <span wire:loading wire:target="triggerBackup">Memproses...</span>
                </button>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800">Riwayat Backup</h3>
                </div>
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">File</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Ukuran</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Dibuat Oleh</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($backups as $b)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-sm font-medium text-slate-900">{{ $b->nama_file }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $b->ukuran_file }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $b->pembuat->nama_lengkap ?? 'System' }}</td>
                            <td class="px-6 py-4 text-right text-sm text-slate-500">{{ $b->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada backup tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL IP -->
    @if($modalIp)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="$set('modalIp', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpanIp">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">{{ $idIpDiedit ? 'Edit IP' : 'Tambah IP Whitelist' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat IP</label>
                                <input type="text" wire:model="ip_address" class="w-full rounded-lg border-slate-300" placeholder="192.168.1.100">
                                @error('ip_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan / Lokasi</label>
                                <input type="text" wire:model="keterangan_ip" class="w-full rounded-lg border-slate-300" placeholder="Komputer Admin 1">
                                @error('keterangan_ip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" wire:click="$set('modalIp', false)" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>