<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Antrian Pemeriksaan</h1>
            <p class="text-gray-500">Pasien yang menunggu giliran diperiksa hari ini</p>
        </div>
        <div class="text-right">
            <span class="block text-sm text-gray-500">Tanggal</span>
            <span class="font-bold text-gray-800">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</span>
        </div>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    @if($antrians->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <div class="text-6xl mb-4">☕</div>
            <h3 class="text-xl font-bold text-gray-800">Tidak Ada Antrian</h3>
            <p class="text-gray-500 mt-2">Belum ada pasien yang menunggu untuk diperiksa saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($antrians as $antrian)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition relative">
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if($antrian->status == 'dipanggil')
                        <span class="animate-pulse bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                            📢 SEDANG DIPANGGIL
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold border border-gray-200">
                            MENUNGGU
                        </span>
                    @endif
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-black border border-blue-100">
                            {{ $antrian->nomor_antrian }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $antrian->pasien->nama_lengkap }}</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ $antrian->pasien->no_rekam_medis }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 mb-6 bg-gray-50 p-3 rounded-lg">
                        <div class="flex justify-between">
                            <span>Umur:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} Tahun</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Poli:</span>
                            <span class="font-medium">{{ $antrian->poli->nama_poli }}</span>
                        </div>
                        @if(!$isDokter)
                        <div class="flex justify-between">
                            <span>Dokter:</span>
                            <span class="font-medium">{{ $antrian->jadwal->dokter->pengguna->nama_lengkap }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        @if($antrian->status == 'menunggu')
                            <button wire:click="panggil({{ $antrian->id }})" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 rounded-lg transition shadow-sm">
                                📢 Panggil
                            </button>
                        @endif
                        
                        <button wire:click="mulaiPeriksa({{ $antrian->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition shadow-sm flex items-center justify-center gap-2">
                            <span>🩺</span> Periksa
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
