<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 -z-10"></div>
            
            <!-- Step 1 -->
            <div class="flex flex-col items-center bg-gray-50 px-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white {{ $langkah >= 1 ? 'bg-medis-600' : 'bg-gray-300' }}">1</div>
                <span class="text-xs font-medium mt-2 text-gray-600">Cek Data</span>
            </div>
            
            <!-- Step 2 -->
            <div class="flex flex-col items-center bg-gray-50 px-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white {{ $langkah >= 2 ? 'bg-medis-600' : 'bg-gray-300' }}">2</div>
                <span class="text-xs font-medium mt-2 text-gray-600">Pilih Poli</span>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center bg-gray-50 px-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white {{ $langkah >= 3 ? 'bg-medis-600' : 'bg-gray-300' }}">3</div>
                <span class="text-xs font-medium mt-2 text-gray-600">Tiket</span>
            </div>
        </div>
    </div>

    <!-- Step 1: Cek Data Pasien -->
    @if($langkah == 1)
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Identifikasi Pasien</h2>
        
        @if (session()->has('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="cariPasien">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk Kependudukan (NIK)</label>
                <input type="text" wire:model="nik" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-medis-500 focus:border-medis-500 transition" placeholder="Masukkan 16 digit NIK Anda">
                @error('nik') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="w-full bg-medis-600 text-white font-bold py-3 rounded-xl shadow hover:bg-medis-700 transition">
                Cari Data Saya
            </button>
        </form>
        <div class="mt-6 text-center text-sm text-gray-500">
            Belum pernah berobat? Silakan datang langsung ke loket pendaftaran pertama kali.
        </div>
    </div>
    @endif

    <!-- Step 2: Pilih Layanan -->
    @if($langkah == 2)
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-6">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl">👤</div>
            <div>
                <h3 class="font-bold text-gray-900 text-lg">{{ $pasien->nama_lengkap }}</h3>
                <p class="text-gray-500 text-sm">RM: {{ $pasien->no_rekam_medis }}</p>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Tujuan Berobat</h2>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Poli</label>
            <select wire:model.live="poli_id" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-medis-500">
                <option value="">-- Pilih Poli --</option>
                @foreach($daftarPoli as $poli)
                    <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                @endforeach
            </select>
        </div>

        @if($poli_id)
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Dokter & Jadwal (Hari Ini)</label>
                <div class="space-y-3">
                    @forelse($daftarJadwal as $jadwal)
                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-blue-50 transition {{ $jadwal_id == $jadwal->id ? 'border-medis-500 bg-blue-50 ring-1 ring-medis-500' : 'border-gray-200' }}">
                        <input type="radio" wire:model="jadwal_id" value="{{ $jadwal->id }}" class="w-4 h-4 text-medis-600 border-gray-300 focus:ring-medis-500">
                        <div class="ml-3 flex-1">
                            <span class="block text-sm font-bold text-gray-900">{{ $jadwal->dokter->pengguna->nama_lengkap ?? 'Dokter Umum' }}</span>
                            <span class="block text-sm text-gray-500">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                        </div>
                    </label>
                    @empty
                    <div class="text-center p-4 bg-gray-50 rounded-lg text-gray-500 text-sm">
                        Tidak ada dokter yang praktek di poli ini hari ini.
                    </div>
                    @endforelse
                </div>
            </div>
        @endif

        <div class="flex gap-4">
            <button wire:click="$set('langkah', 1)" class="w-1/3 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 transition">
                Kembali
            </button>
            <button wire:click="ambilAntrian" class="w-2/3 bg-medis-600 text-white font-bold py-3 rounded-xl shadow hover:bg-medis-700 transition disabled:opacity-50 disabled:cursor-not-allowed" {{ !$jadwal_id ? 'disabled' : '' }}>
                Konfirmasi Antrian
            </button>
        </div>
    </div>
    @endif

    <!-- Step 3: Tiket Antrian -->
    @if($langkah == 3 && $tiketAntrian)
    <div class="bg-white rounded-2xl shadow-xl border-t-8 border-medis-500 p-8 text-center max-w-md mx-auto">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
            ✅
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Berhasil Mendaftar!</h2>
        <p class="text-gray-500 mb-8">Silakan screenshot bukti antrian ini.</p>

        <div class="bg-gray-50 p-6 rounded-xl border border-dashed border-gray-300 mb-8">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">NOMOR ANTRIAN ANDA</p>
            <div class="text-5xl font-black text-medis-600 tracking-wider mb-2">{{ $tiketAntrian->nomor_antrian }}</div>
            <div class="text-sm font-medium text-gray-800">{{ $tiketAntrian->poli->nama_poli }}</div>
            <div class="text-xs text-gray-500 mt-4 pt-4 border-t border-gray-200">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>

        <a href="/" class="block w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-gray-800 transition">
            Kembali ke Beranda
        </a>
    </div>
    @endif

</div>
