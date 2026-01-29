<div class="py-12 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-black text-slate-900 mb-4">Jadwal Praktik Dokter</h1>
            <p class="text-slate-600 max-w-2xl mx-auto">Temukan jadwal dokter spesialis dan umum kami. Silakan datang 30 menit sebelum jadwal praktik dimulai untuk pendaftaran.</p>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="w-full">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Cari Dokter</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama dokter..." class="w-full rounded-lg border-slate-300 text-sm pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Poli -->
                <div class="w-full">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Pilih Poli</label>
                    <select wire:model.live="filterPoli" class="w-full rounded-lg border-slate-300 text-sm">
                        <option value="">Semua Poli</option>
                        @foreach($poliList as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Hari -->
                <div class="w-full">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Pilih Hari</label>
                    <select wire:model.live="filterHari" class="w-full rounded-lg border-slate-300 text-sm">
                        <option value="">Semua Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid Jadwal -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jadwal as $j)
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="relative w-14 h-14 flex-shrink-0">
                        @if($j->dokter->pengguna->foto_profil)
                            <img src="{{ asset('storage/' . $j->dokter->pengguna->foto_profil) }}" class="w-full h-full object-cover rounded-full border-2 border-slate-100">
                        @else
                            <div class="w-full h-full bg-slate-100 rounded-full flex items-center justify-center text-slate-500 text-lg font-bold border-2 border-slate-50">
                                {{ substr($j->dokter->pengguna->nama_lengkap ?? 'D', 0, 1) }}
                            </div>
                        @endif
                        <div class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <div class="overflow-hidden">
                        <h3 class="font-bold text-slate-900 truncate group-hover:text-emerald-600 transition">{{ $j->dokter->pengguna->nama_lengkap ?? 'Dokter' }}</h3>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide truncate">{{ $j->poli->nama_poli ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="border-t border-slate-100 pt-4 flex justify-between items-center">
                    <div>
                        <div class="flex items-center gap-1.5 mb-1">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-bold text-slate-700">{{ $j->hari }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-semibold">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('publik.ambil-antrian') }}" wire:navigate class="px-4 py-2 bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition shadow-sm">
                        Daftar
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-slate-400 bg-white rounded-xl border border-dashed border-slate-300">
                Tidak ada jadwal dokter yang sesuai filter.
            </div>
            @endforelse
        </div>
    </div>
</div>
