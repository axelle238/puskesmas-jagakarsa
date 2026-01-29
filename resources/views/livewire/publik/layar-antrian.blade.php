<div class="min-h-screen bg-gray-900 text-white font-sans overflow-hidden flex flex-col" wire:poll.2s>
    
    <!-- Header -->
    <header class="bg-medis-600 p-6 flex justify-between items-center shadow-lg z-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center text-medis-600 font-bold text-3xl shadow">
                +
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-wider uppercase">Puskesmas Jagakarsa</h1>
                <p class="text-medis-100 text-lg">Integrasi Layanan Primer (ILP)</p>
            </div>
        </div>
        <div class="text-right">
            <div class="text-4xl font-mono font-bold" id="clock">00:00</div>
            <div class="text-medis-200">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Panggilan Utama (Big Screen) -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            <div class="flex-1 bg-white rounded-3xl shadow-2xl flex flex-col items-center justify-center p-12 text-gray-900 relative overflow-hidden border-8 border-medis-500">
                
                @if($sedangDipanggil)
                    <div class="absolute top-0 left-0 w-full bg-medis-500 text-white text-center py-4 text-2xl font-bold uppercase tracking-widest animate-pulse">
                        Sedang Memanggil
                    </div>
                    
                    <p class="text-3xl text-gray-500 font-medium uppercase mb-4 mt-8">Nomor Antrian</p>
                    
                    <div class="text-[12rem] leading-none font-black text-medis-600 tracking-tighter" id="nomor-panggilan">
                        {{ $sedangDipanggil->nomor_antrian }}
                    </div>
                    
                    <div class="mt-8 text-center">
                        <div class="text-4xl font-bold text-gray-800 mb-2">{{ $sedangDipanggil->poli->nama_poli }}</div>
                        <div class="text-2xl text-gray-500">{{ $sedangDipanggil->jadwal->dokter->pengguna->nama_lengkap }}</div>
                    </div>

                    <!-- Hidden Input untuk Trigger Audio via JS -->
                    <input type="hidden" id="audio-trigger" value="{{ $sedangDipanggil->nomor_antrian }}|{{ $sedangDipanggil->poli->nama_poli }}">
                @else
                    <div class="text-center opacity-50">
                        <div class="text-9xl mb-4">⏸️</div>
                        <h2 class="text-4xl font-bold">Belum Ada Panggilan</h2>
                        <p class="text-xl mt-4">Mohon menunggu antrian dimulai...</p>
                    </div>
                @endif
            </div>

            <!-- Video Edukasi / Running Text (Placeholder) -->
            <div class="h-32 bg-gray-800 rounded-2xl flex items-center justify-center border border-gray-700 relative overflow-hidden">
                <div class="absolute inset-0 flex items-center whitespace-nowrap animate-marquee">
                    <span class="text-2xl mx-4">📢 Jagalah kebersihan lingkungan.</span>
                    <span class="text-2xl mx-4">😷 Gunakan masker jika batuk/pilek.</span>
                    <span class="text-2xl mx-4">👶 Jangan lupa jadwal imunisasi anak.</span>
                    <span class="text-2xl mx-4">🚭 Kawasan tanpa rokok.</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: List Antrian Per Poli -->
        <div class="bg-gray-800 rounded-3xl p-6 border border-gray-700 shadow-xl overflow-hidden flex flex-col">
            <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-600 pb-4 flex items-center gap-3">
                <span>📋</span> Status Poli
            </h2>
            
            <div class="space-y-4 flex-1 overflow-y-auto">
                @foreach($antrianPerPoli as $status)
                <div class="bg-gray-700 rounded-xl p-5 flex justify-between items-center border-l-8 border-medis-500">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $status->poli->nama_poli }}</h3>
                        <p class="text-gray-400 text-sm">Lokasi: {{ $status->poli->lokasi_ruangan }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-400 uppercase block">Sedang Dilayani</span>
                        <span class="text-4xl font-mono font-bold text-medis-400">{{ $status->nomor_terakhir }}</span>
                    </div>
                </div>
                @endforeach

                @if($antrianPerPoli->isEmpty())
                    <p class="text-center text-gray-500 py-10">Belum ada aktivitas poli.</p>
                @endif
            </div>
        </div>

    </main>

    <!-- Script Jam & Audio -->
    <script>
        // Jam Digital
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }, 1000);

        // Text to Speech Logic
        let lastCalled = '';

        // Fungsi Membaca
        function announce(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = 1;
                window.speechSynthesis.speak(utterance);
            }
        }

        // Cek Perubahan Panggilan (Polling manual via JS check value hidden input)
        setInterval(() => {
            const triggerEl = document.getElementById('audio-trigger');
            if (triggerEl) {
                const currentVal = triggerEl.value;
                if (currentVal !== lastCalled) {
                    lastCalled = currentVal;
                    const [nomor, poli] = currentVal.split('|');
                    
                    // Format bacaan: "Nomor Antrian, A, Kosong, Satu. Silakan ke, Poli Umum"
                    // Memisahkan huruf dan angka agar dibaca jelas
                    const huruf = nomor.charAt(0);
                    const angka = parseInt(nomor.substring(2)); 
                    
                    const kalimat = `Nomor Antrian, ${huruf}, ${angka}. Silakan menuju, ${poli}`;
                    announce(kalimat);
                }
            }
        }, 1000);
    </script>

    <style>
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 20s linear infinite;
        }
    </style>
</div>
