<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Antrian Laboratorium</h1>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('pesan') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- List Antrian -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 font-bold text-gray-700">Menunggu Pemeriksaan</div>
            <div class="divide-y divide-gray-100">
                @forelse($antrian_lab as $item)
                    <div wire:click="pilihPermintaan({{ $item->id }})" class="p-4 hover:bg-blue-50 cursor-pointer transition {{ $permintaan_terpilih && $permintaan_terpilih->id == $item->id ? 'bg-blue-100' : '' }}">
                        <div class="font-bold text-gray-800">{{ $item->rekamMedis->pasien->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">RM: {{ $item->rekamMedis->pasien->no_rekam_medis }}</div>
                        <div class="text-xs text-blue-600 mt-1">Dr. {{ $item->dokter->pengguna->nama_lengkap }}</div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Tidak ada antrian lab.</div>
                @endforelse
            </div>
        </div>

        <!-- Form Input -->
        <div class="md:col-span-2">
            @if($permintaan_terpilih)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Input Hasil: {{ $permintaan_terpilih->rekamMedis->pasien->nama_lengkap }}</h2>
                    
                    <div class="bg-yellow-50 p-3 rounded mb-4 text-sm text-yellow-800">
                        <strong>Catatan Dokter:</strong> {{ $permintaan_terpilih->catatan_permintaan ?? '-' }}
                    </div>

                    <table class="w-full text-sm mb-6">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="pb-2">Parameter</th>
                                <th class="pb-2">Hasil</th>
                                <th class="pb-2">Satuan</th>
                                <th class="pb-2">Nilai Rujukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil_input as $index => $row)
                            <tr>
                                <td class="py-2 pr-2">
                                    <input type="text" wire:model="hasil_input.{{ $index }}.parameter" class="w-full border rounded px-2 py-1 bg-gray-50" readonly>
                                </td>
                                <td class="py-2 pr-2">
                                    <input type="text" wire:model="hasil_input.{{ $index }}.nilai" class="w-full border rounded px-2 py-1 focus:ring-blue-500">
                                </td>
                                <td class="py-2 pr-2">
                                    <input type="text" wire:model="hasil_input.{{ $index }}.satuan" class="w-full border rounded px-2 py-1 bg-gray-50" readonly>
                                </td>
                                <td class="py-2">
                                    <input type="text" wire:model="hasil_input.{{ $index }}.rujukan" class="w-full border rounded px-2 py-1 bg-gray-50" readonly>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button wire:click="simpanHasil" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">Simpan & Selesai</button>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-12 text-center text-gray-400">
                    Pilih pasien dari daftar antrian untuk memasukkan hasil lab.
                </div>
            @endif
        </div>
    </div>
</div>
