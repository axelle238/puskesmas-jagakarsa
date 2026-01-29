<div wire:poll.10s> <!-- Update otomatis setiap 10 detik -->
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Antrian Pasien Hari Ini</h1>
            <p class="text-slate-500">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }} | 
                <span class="font-bold text-emerald-600">{{ $sisaAntrian }} Menunggu</span>
            </p>
        </div>
        
        <div class="w-full md:w-64">
            <select wire:model.live="filterPoli" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">-- Semua Poli --</option>
                @foreach($daftarPoli as $poli)
                    <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Antrian Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($antrianList as $antrian)
        <div class="bg-white rounded-xl shadow-sm border {{ $antrian->status == 'dipanggil' ? 'border-blue-400 ring-2 ring-blue-100' : 'border-slate-200' }} overflow-hidden flex flex-col transition-all hover:shadow-md">
            
            <!-- Card Header: Nomor & Status -->
            <div class="p-4 flex justify-between items-start {{ $antrian->status == 'dipanggil' ? 'bg-blue-50' : 'bg-slate-50' }}">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Nomor Antrian</span>
                    <h3 class="text-3xl font-black text-slate-900">{{ $antrian->nomor_antrian }}</h3>
                </div>
                <div>
                    @if($antrian->status == 'dipanggil')
                        <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded animate-pulse">DIPANGGIL</span>
                    @else
                        <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-1 rounded">MENUNGGU</span>
                    @endif
                </div>
            </div>

            <!-- Card Body: Info Pasien -->
            <div class="p-4 flex-1">
                <p class="font-bold text-lg text-slate-800 mb-1">{{ $antrian->pasien->nama_lengkap }}</p>
                <p class="text-sm text-slate-500 mb-3">RM: {{ $antrian->pasien->no_rekam_medis }}</p>
                
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Daftar: {{ $antrian->created_at->format('H:i') }}
                    @if($antrian->poli)
                    <span class="mx-1">•</span>
                    {{ $antrian->poli->nama_poli }}
                    @endif
                </div>
            </div>

            <!-- Card Footer: Actions -->
            <div class="p-4 border-t border-slate-100 grid grid-cols-2 gap-3 bg-white">
                @if($antrian->status == 'menunggu')
                    <button wire:click="panggilAntrian({{ $antrian->id }})" class="col-span-2 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        Panggil Pasien
                    </button>
                    <button wire:click="lewatiAntrian({{ $antrian->id }})" class="w-full py-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-lg font-bold text-sm transition-colors" onclick="confirm('Lewati pasien ini?') || event.stopImmediatePropagation()">
                        Lewati
                    </button>
                @elseif($antrian->status == 'dipanggil')
                    <button wire:click="mulaiPeriksa({{ $antrian->id }})" class="col-span-2 w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-sm transition-colors flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Mulai Periksa
                    </button>
                    <button wire:click="panggilAntrian({{ $antrian->id }})" class="w-full py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg font-bold text-sm transition-colors">
                        Panggil Ulang
                    </button>
                    <button wire:click="lewatiAntrian({{ $antrian->id }})" class="w-full py-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-lg font-bold text-sm transition-colors">
                        Tidak Hadir
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-slate-50 rounded-xl border border-dashed border-slate-300">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada antrian menunggu</h3>
            <p class="mt-1 text-sm text-slate-500">Semua pasien telah dilayani atau belum ada pendaftaran baru.</p>
        </div>
        @endforelse
    </div>

    <!-- Notification Sound Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('bunyikan-bel', (event) => {
                // Logika sederhana beep / alert untuk demo.
                // Idealnya memutar file audio: new Audio('/sounds/bell.mp3').play();
                console.log('Memanggil nomor: ' + event.nomor);
                
                // Visual feedback (Toast or simple alert if urgent)
                // alert('Panggilan untuk nomor: ' + event.nomor); 
            });
        });
    </script>
</div>