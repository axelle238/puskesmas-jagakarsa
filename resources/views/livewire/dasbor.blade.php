<div>
    <!-- Header Page -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ringkasan Operasional</h1>
            <p class="text-gray-500">Pantau aktivitas Puskesmas secara real-time</p>
        </div>
        <div>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-400">
                Live Update
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Antrian Hari Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAntrianHariIni }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-500">
                    👥
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <span class="{{ $antrianMenunggu > 0 ? 'text-orange-500 font-bold' : 'text-green-500' }}">
                    {{ $antrianMenunggu }}
                </span> menunggu dipanggil
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pasien</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalPasien) }}</h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-500">
                    🗂️
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Terdaftar dalam database
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dokter Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $dokterAktif }}</h3>
                </div>
                <div class="p-2 bg-purple-50 rounded-lg text-purple-500">
                    👨‍⚕️
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Siap melayani hari ini
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Layanan ILP</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">4</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                    🔄
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Klaster Layanan Primer
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Antrian Masuk Terkini</h3>
            <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-3">No. Antrian</th>
                        <th class="px-6 py-3">Pasien</th>
                        <th class="px-6 py-3">Poli Tujuan</th>
                        <th class="px-6 py-3">Waktu Masuk</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($antrianTerbaru as $antrian)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $antrian->nomor_antrian }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $antrian->pasien->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $antrian->pasien->no_rekam_medis }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold">
                                {{ $antrian->poli->nama_poli }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $antrian->created_at->format('H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($antrian->status == 'menunggu')
                                <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-xs font-bold">Menunggu</span>
                            @elseif($antrian->status == 'dipanggil')
                                <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs font-bold">Dipanggil</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-bold">{{ ucfirst($antrian->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs border border-blue-200 px-3 py-1 rounded hover:bg-blue-50 transition">
                                Panggil
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                            Belum ada antrian hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>