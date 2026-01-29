<div>
    <div class="flex justify-between items-center mb-6 print:hidden">
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
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 print:hidden">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="space-y-6 print:hidden">
        @forelse($reseps as $rm)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ showPrint: false }">
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
                <div class="text-right flex items-center gap-4">
                    <div>
                        <div class="text-xs text-gray-500">Dokter Peresep</div>
                        <div class="font-medium text-gray-800">{{ $rm->dokter->pengguna->nama_lengkap }}</div>
                    </div>
                    <button @click="printResep('resep-{{ $rm->id }}')" class="text-gray-500 hover:text-gray-700 p-2" title="Cetak Resep">
                        🖨️
                    </button>
                </div>
            </div>

            <!-- List Obat (Tampilan Web) -->
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

            <!-- Layout Cetak Tersembunyi -->
            <div id="resep-{{ $rm->id }}" class="hidden print:block print:visible p-8 bg-white text-black font-serif">
                <div class="border-b-2 border-black pb-4 mb-6 text-center">
                    <h2 class="text-xl font-bold uppercase tracking-widest">Puskesmas Jagakarsa</h2>
                    <p class="text-xs">Jl. Moh. Kahfi 1, Jagakarsa, Jakarta Selatan</p>
                    <p class="text-xs">Telp: (021) 786-xxxx | SIP: {{ $rm->dokter->sip }}</p>
                </div>
                
                <div class="flex justify-between items-end mb-6 text-sm">
                    <div>
                        <p>Dokter: <strong>{{ $rm->dokter->pengguna->nama_lengkap }}</strong></p>
                        <p>Poli: {{ $rm->poli->nama_poli }}</p>
                    </div>
                    <div class="text-right">
                        <p>Tanggal: {{ $rm->created_at->format('d/m/Y') }}</p>
                        <p>No. RM: {{ $rm->pasien->no_rekam_medis }}</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold border-b border-gray-400 mb-2">R/ Resep Obat</h3>
                    <ul class="list-none space-y-4">
                        @foreach($rm->resepDetail as $obat)
                        <li class="flex justify-between items-start">
                            <div>
                                <span class="font-bold text-lg">R/ {{ $obat->nama_obat }}</span>
                                <div class="ml-4 italic text-sm">S {{ $obat->pivot->aturan_pakai }}</div>
                            </div>
                            <div class="font-bold">No. {{ $obat->pivot->jumlah }} ({{ $obat->satuan }})</div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-12 pt-4 border-t border-black flex justify-between text-sm">
                    <div>
                        <p>Pro: <strong>{{ $rm->pasien->nama_lengkap }}</strong></p>
                        <p>Usia: {{ \Carbon\Carbon::parse($rm->pasien->tanggal_lahir)->age }} Tahun</p>
                        <p>Alamat: {{ $rm->pasien->alamat_lengkap }}</p>
                    </div>
                    <div class="text-center w-32">
                        <p class="mb-8">Apoteker</p>
                        <div class="border-b border-black"></div>
                    </div>
                </div>
                
                <div class="mt-8 text-center text-xs italic text-gray-500">
                    -- Semoga Lekas Sembuh --
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
    
    <div class="mt-6 print:hidden">
        {{ $reseps->links() }}
    </div>

    <!-- Script Cetak -->
    <script>
        function printResep(elementId) {
            const printContent = document.getElementById(elementId).innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload(); // Reload agar event listener Livewire kembali aktif
        }
    </script>
</div>