<div class="py-12 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-black text-slate-900 mb-4">Jadwal Praktik Dokter</h1>
            <p class="text-slate-600 max-w-2xl mx-auto">Temukan jadwal dokter spesialis dan umum kami. Silakan datang 30 menit sebelum jadwal praktik dimulai untuk pendaftaran.</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 flex flex-col md:flex-row gap-4 justify-center">
            <div class="w-full md:w-64">
                <label class="block text-xs font-bold text-slate-500 mb-1">Pilih Poli</label>
                <select wire:model.live="filterPoli" class="w-full rounded-lg border-slate-300 text-sm">
                    <option value="">Semua Poli</option>
                    @foreach($poliList as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-64">
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

        <!-- Grid Jadwal -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jadwal as $j)
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center text-slate-500 text-lg font-bold">
                        {{ substr($j->dokter->pengguna->nama_lengkap ?? 'Dr', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $j->dokter->pengguna->nama_lengkap ?? 'Dokter' }}</h3>
                        <p class="text-xs text-emerald-600 font-bold uppercase tracking-wide">{{ $j->poli->nama_poli ?? '-' }}</p>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-4 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-slate-700">{{ $j->hari }}</p>
                        <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</p>
                    </div>
                    <a href="{{ route('publik.ambil-antrian') }}" class="px-3 py-1.5 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-slate-700">
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
