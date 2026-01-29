<div>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Selamat Datang, {{ auth()->user()->nama_lengkap }} 👋</h1>
        <p class="text-slate-500">Berikut adalah ringkasan aktivitas Puskesmas hari ini.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Pasien -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pasien Hari Ini</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalPasienHariIni }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Data Realtime
                </p>
            </div>
            <div class="bg-blue-50 text-blue-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Sedang Dilayani -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Sedang Dilayani</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $sedangDilayani }}</h3>
                <p class="text-xs text-orange-600 font-medium mt-2">Dalam Ruang Periksa</p>
            </div>
            <div class="bg-orange-50 text-orange-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
        </div>

        <!-- Card 3: Pendapatan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pendapatan (Est)</p>
                <h3 class="text-3xl font-black text-slate-800">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 font-medium mt-2">Belum termasuk BPJS</p>
            </div>
            <div class="bg-emerald-50 text-emerald-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Peringatan Stok -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Stok Kritis</p>
                <h3 class="text-3xl font-black {{ $obatMenipis->count() > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $obatMenipis->count() }}</h3>
                <p class="text-xs text-slate-400 font-medium mt-2">Jenis Obat Perlu Restock</p>
            </div>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Antrian Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-bold text-slate-900">Antrian Terbaru</h2>
                <a href="{{ route('medis.antrian') }}" wire:navigate class="text-sm text-emerald-600 font-medium hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">No. Antrian</th>
                            <th class="px-6 py-3 font-medium">Pasien</th>
                            <th class="px-6 py-3 font-medium">Poli Tujuan</th>
                            <th class="px-6 py-3 font-medium">Waktu Daftar</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($antrianTerbaru as $a)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $a->nomor_antrian }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $a->pasien->nama_lengkap }}</div>
                                <div class="text-xs text-slate-400">{{ $a->pasien->no_rekam_medis }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-medium">{{ $a->poli->nama_poli }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($a->created_at)->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                @if($a->status == 'menunggu')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Menunggu
                                    </span>
                                @elseif($a->status == 'dipanggil')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Dipanggil
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                Belum ada antrian hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Stok Kritis -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden h-fit">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-bold text-slate-900">Peringatan Stok Obat</h2>
                <a href="{{ route('farmasi.stok') }}" wire:navigate class="text-sm text-emerald-600 font-medium hover:text-emerald-700">Kelola</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($obatMenipis as $obat)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div>
                        <p class="font-medium text-slate-900">{{ $obat->nama_obat }}</p>
                        <p class="text-xs text-slate-500">{{ $obat->kategori }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600">{{ $obat->stok_saat_ini }} <span class="text-xs font-normal text-slate-400">{{ $obat->satuan }}</span></p>
                        <p class="text-xs text-slate-400">Min: {{ $obat->stok_minimum }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-sm">
                    <svg class="w-8 h-8 mx-auto mb-2 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Semua stok aman.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
