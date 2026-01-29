<div class="min-h-screen bg-slate-900 text-white p-6 font-sans flex flex-col" wire:poll.5s>
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8 border-b border-slate-700 pb-4">
        <div class="flex items-center gap-4">
            <div class="bg-emerald-600 p-3 rounded-xl">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-tight">PUSKESMAS JAGAKARSA</h1>
                <p class="text-emerald-400 text-lg">Sistem Antrian Terintegrasi</p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-4xl font-black font-mono">{{ now()->format('H:i') }}</h2>
            <p class="text-slate-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 grid grid-cols-12 gap-8">
        
        <!-- Left: Video / Informasi & Panggilan Utama -->
        <div class="col-span-12 lg:col-span-7 flex flex-col gap-6">
            
            <!-- Video Placeholder -->
            <div class="flex-1 bg-black rounded-3xl overflow-hidden shadow-2xl relative border border-slate-700">
                <!-- Bisa diganti iframe youtube edukasi -->
                <div class="absolute inset-0 flex items-center justify-center bg-slate-800">
                    <div class="text-center opacity-50">
                        <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xl">Video Edukasi Kesehatan</p>
                    </div>
                </div>
            </div>

            <!-- Panggilan Utama (Big Card) -->
            <div class="h-64 bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-3xl shadow-2xl flex items-center justify-between p-8 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 pattern-dots"></div>
                
                <div class="relative z-10">
                    <p class="text-emerald-200 text-2xl font-bold uppercase tracking-widest mb-2">Panggilan Nomor</p>
                    <h1 class="text-[120px] font-black leading-none tracking-tighter text-white drop-shadow-lg">
                        {{ $panggilanTerakhir->nomor_antrian ?? '---' }}
                    </h1>
                </div>

                <div class="text-right relative z-10 max-w-md">
                    <p class="text-emerald-200 text-xl font-bold uppercase mb-2">Silakan Menuju</p>
                    <h2 class="text-5xl font-bold text-white mb-4 leading-tight">
                        {{ $panggilanTerakhir->poli->nama_poli ?? 'LOKET' }}
                    </h2>
                    @if($panggilanTerakhir)
                    <div class="inline-block bg-white/20 backdrop-blur-md rounded-xl px-6 py-3">
                        <p class="text-white text-lg font-medium">{{ $panggilanTerakhir->jadwal->dokter->pengguna->nama_lengkap ?? '-' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Daftar Antrian Poli -->
        <div class="col-span-12 lg:col-span-5 grid grid-rows-4 gap-4">
            @foreach($rekapPoli as $poli)
            <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $poli['nama_poli'] }}</h3>
                    <p class="text-slate-400 text-sm">{{ $poli['dokter'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-1">Dipanggil</p>
                    <div class="text-4xl font-black text-emerald-400">{{ $poli['nomor_dipanggil'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    <!-- Running Text Footer -->
    <div class="mt-8 bg-slate-800 rounded-full py-3 px-6 overflow-hidden">
        <p class="text-lg whitespace-nowrap animate-marquee">
            Selamat Datang di Puskesmas Jagakarsa. Budayakan antri dan jaga kebersihan. Gunakan masker jika sedang batuk atau flu. Layanan Gawat Darurat 24 Jam.
        </p>
    </div>

    <style>
        .animate-marquee {
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
    </style>
</div>