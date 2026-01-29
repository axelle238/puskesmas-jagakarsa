<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Antrian Farmasi</h1>
            <p class="text-gray-500">Kelola penyiapan dan penyerahan obat pasien</p>
        </div>
        
        <!-- Filter Tabs -->
        <div class="bg-white p-1 rounded-lg border border-gray-200 flex">
            <button wire:click="$set('filterStatus', 'menunggu')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $filterStatus == 'menunggu' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                🕒 Menunggu
            </button>
            <button wire:click="$set('filterStatus', 'disiapkan')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $filterStatus == 'disiapkan' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-50' }}">
                ⚙️ Sedang Disiapkan
            </button>
            <button wire:click="$set('filterStatus', 'selesai')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $filterStatus == 'selesai' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                ✅ Selesai
            </button>
        </div>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($reseps as $rm)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header Resep -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                        {{ substr($rm->pasien->nama_lengkap, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $rm->pasien->nama_lengkap }}</h3>
                        <p class="text-xs text-gray-500">RM: {{ $rm->pasien->no_rekam_medis }} • {{ $rm->poli->nama_poli }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500">Dokter Peresep</div>
                    <div class="font-medium text-gray-800">{{ $rm->dokter->pengguna->nama_lengkap }}</div>
                </div>
            </div>

            <!-- List Obat -->
            <div class="p-6">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="pb-2">Nama Obat</th>
                            <th class="pb-2">Jumlah</th>
                            <th class="pb-2">Aturan Pakai</th>
                            <th class="pb-2 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($rm->resepDetail as $obat)
                        <tr class="group">
                            <td class="py-3 font-medium text-gray-800">{{ $obat->nama_obat }}</td>
                            <td class="py-3">{{ $obat->pivot->jumlah }} {{ $obat->satuan }}</td>
                            <td class="py-3 text-gray-600 italic">{{ $obat->pivot->aturan_pakai }}</td>
                            <td class="py-3 text-right">
                                @if($obat->pivot->status_pengambilan == 'menunggu')
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">Menunggu</span>
                                @elseif($obat->pivot->status_pengambilan == 'disiapkan')
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded">Disiapkan</span>
                                @else
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-50">
                    @if($filterStatus == 'menunggu')
                        <button wire:click="prosesSemua({{ $rm->id }}, 'disiapkan')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow transition text-sm">
                            ⚙️ Proses Semua Obat
                        </button>
                    @elseif($filterStatus == 'disiapkan')
                        <button wire:click="prosesSemua({{ $rm->id }}, 'selesai')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition text-sm">
                            ✅ Serahkan ke Pasien
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <div class="text-4xl mb-4">🍃</div>
            <h3 class="text-lg font-bold text-gray-800">Tidak ada resep</h3>
            <p class="text-gray-500">Tidak ada resep dengan status '{{ ucfirst($filterStatus) }}' saat ini.</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $reseps->links() }}
    </div>
</div>
