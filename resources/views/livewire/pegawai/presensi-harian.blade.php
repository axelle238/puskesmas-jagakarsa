<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Presensi Pegawai</h1>
        <p class="text-slate-500">Catat kehadiran kerja anda setiap hari.</p>
    </div>

    @if(session('sukses'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            {{ session('sukses') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Kartu Absensi -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-100 p-8 text-center max-w-lg mx-auto mb-10">
        <h2 class="text-lg font-bold text-slate-700 mb-2">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h2>
        <div class="text-4xl font-black text-slate-900 mb-8 font-mono">
            {{ \Carbon\Carbon::now()->format('H:i') }}
        </div>

        @if($statusHariIni == 'selesai')
            <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100">
                <svg class="w-16 h-16 text-emerald-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="font-bold text-emerald-800 text-xl">Anda Sudah Pulang</h3>
                <p class="text-emerald-600">Terima kasih atas dedikasi anda hari ini.</p>
                <div class="mt-4 text-sm text-slate-500">
                    Masuk: {{ $presensiHariIni->jam_masuk->format('H:i') }} | Pulang: {{ $presensiHariIni->jam_pulang->format('H:i') }}
                </div>
            </div>
        @elseif($statusHariIni == 'masuk')
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 mb-6">
                <p class="text-blue-800 font-bold mb-2">Anda Masuk Pukul</p>
                <p class="text-3xl font-black text-blue-600">{{ $presensiHariIni->jam_masuk->format('H:i') }}</p>
            </div>
            <button wire:click="absenPulang" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg text-lg transition transform hover:scale-105">
                Absen Pulang
            </button>
        @else
            <button wire:click="absenMasuk" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg text-lg transition transform hover:scale-105">
                Absen Masuk
            </button>
        @endif
    </div>

    <!-- Riwayat -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-bold text-slate-800">Riwayat Kehadiran Anda</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Jam Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Jam Pulang</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($riwayat as $r)
                <tr>
                    <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $r->jam_masuk ? $r->jam_masuk->format('H:i') : '-' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $r->jam_pulang ? $r->jam_pulang->format('H:i') : '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800 uppercase">{{ $r->status }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada riwayat.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $riwayat->links() }}</div>
    </div>
</div>
