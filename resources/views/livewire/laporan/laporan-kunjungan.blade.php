<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Kunjungan Pasien</h1>
            <p class="text-gray-500">Rekapitulasi data pelayanan medis</p>
        </div>
        
        <!-- Filter Tanggal -->
        <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm flex items-center gap-2">
            <input type="date" wire:model.live="tanggal_mulai" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
            <span class="text-gray-400">-</span>
            <input type="date" wire:model.live="tanggal_selesai" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100">
            <div class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-1">Total Kunjungan</div>
            <div class="text-3xl font-black text-gray-800">{{ number_format($totalPasien) }}</div>
            <div class="text-sm text-gray-400 mt-2">Pasien diperiksa</div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100">
            <div class="text-xs font-bold text-green-500 uppercase tracking-wider mb-1">Estimasi Pendapatan Jasa</div>
            <div class="text-3xl font-black text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="text-sm text-gray-400 mt-2">Dari tindakan medis</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-purple-100">
            <div class="text-xs font-bold text-purple-500 uppercase tracking-wider mb-1">Poli Teramai</div>
            @if($totalPoli->isNotEmpty())
                @php $topPoli = $totalPoli->sortDesc()->keys()->first(); $count = $totalPoli->sortDesc()->first(); @endphp
                <div class="text-2xl font-black text-gray-800 truncate">{{ $topPoli }}</div>
                <div class="text-sm text-gray-400 mt-2">{{ $count }} Kunjungan</div>
            @else
                <div class="text-2xl font-black text-gray-800">-</div>
            @endif
        </div>
    </div>

    <!-- Tabel Data Detail -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 font-bold text-gray-800">
            Rincian Data
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">No. RM & Pasien</th>
                        <th class="px-6 py-3">Poli & Dokter</th>
                        <th class="px-6 py-3">Diagnosis</th>
                        <th class="px-6 py-3 text-right">Biaya Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kunjungans as $kunjungan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            {{ $kunjungan->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $kunjungan->pasien->nama_lengkap }}</div>
                            <div class="text-xs text-blue-600 font-mono">{{ $kunjungan->pasien->no_rekam_medis }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $kunjungan->poli->nama_poli }}</div>
                            <div class="text-xs text-gray-500">{{ $kunjungan->dokter->pengguna->nama_lengkap }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($kunjungan->diagnosis_kode)
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded font-bold mr-1">{{ $kunjungan->diagnosis_kode }}</span>
                            @endif
                            <span class="text-gray-600 truncate block max-w-xs">{{ Str::limit($kunjungan->asesmen, 50) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono text-gray-700">
                            @php
                                $subtotal = $kunjungan->tindakanDetail->sum('pivot.biaya_saat_ini');
                            @endphp
                            {{ $subtotal > 0 ? 'Rp '.number_format($subtotal, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            Tidak ada data kunjungan pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
