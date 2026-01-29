<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kasir & Pembayaran</h1>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded mb-4 font-bold">{{ session('pesan') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- List Antrian -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 font-bold text-gray-700">Antrian Pembayaran</div>
            <div class="divide-y divide-gray-100">
                @forelse($antrian_kasir as $rm)
                    <div wire:click="pilihPasien({{ $rm->id }})" class="p-4 hover:bg-green-50 cursor-pointer transition {{ $rekam_medis_id == $rm->id ? 'bg-green-100' : '' }}">
                        <div class="font-bold text-gray-800">{{ $rm->pasien->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">RM: {{ $rm->pasien->no_rekam_medis }}</div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Tidak ada tagihan pending.</div>
                @endforelse
            </div>
        </div>

        <!-- Detail Tagihan -->
        <div class="md:col-span-2">
            @if($tagihan_aktif)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center border-b pb-4 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Invoice #{{ $tagihan_aktif->no_tagihan }}</h2>
                            <p class="text-sm text-gray-500">{{ now()->format('d F Y H:i') }}</p>
                        </div>
                        @if($tagihan_aktif->status_bayar == 'lunas')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold text-sm">LUNAS</span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-bold text-sm">BELUM BAYAR</span>
                        @endif
                    </div>

                    <table class="w-full text-sm mb-6">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="p-2 text-left">Item</th>
                                <th class="p-2 text-right">Harga</th>
                                <th class="p-2 text-center">Qty</th>
                                <th class="p-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($tagihan_aktif->detail as $item)
                            <tr>
                                <td class="p-2">{{ $item->item }} <span class="text-xs text-gray-400 block">{{ $item->kategori }}</span></td>
                                <td class="p-2 text-right">{{ number_format($item->harga_satuan) }}</td>
                                <td class="p-2 text-center">{{ $item->jumlah }}</td>
                                <td class="p-2 text-right font-bold">{{ number_format($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200">
                            <tr>
                                <td colspan="3" class="p-3 text-right font-bold text-lg">TOTAL TAGIHAN</td>
                                <td class="p-3 text-right font-black text-xl text-blue-600">Rp {{ number_format($tagihan_aktif->total_biaya) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    @if($tagihan_aktif->status_bayar != 'lunas')
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Metode Bayar</label>
                                    <select wire:model="metode_bayar" class="w-full border rounded p-2">
                                        <option value="tunai">Tunai</option>
                                        <option value="qris">QRIS</option>
                                        <option value="debit">Kartu Debit</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Uang (Rp)</label>
                                    <input type="number" wire:model="jumlah_bayar" class="w-full border rounded p-2 font-mono">
                                </div>
                            </div>
                            <button wire:click="prosesBayar" class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow-lg text-lg">
                                BAYAR SEKARANG
                            </button>
                        </div>
                    @else
                        <button onclick="window.print()" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-lg">
                            🖨️ CETAK STRUK
                        </button>
                        
                        <!-- Struk Print Only -->
                        <div class="hidden print:block absolute top-0 left-0 w-full h-full bg-white p-8 z-50">
                            <div class="text-center font-mono">
                                <h2 class="text-xl font-bold">PUSKESMAS JAGAKARSA</h2>
                                <p>Jl. Moh Kahfi 1, Jakarta Selatan</p>
                                <p>--------------------------------</p>
                                <p class="text-left my-2">No: {{ $tagihan_aktif->no_tagihan }}<br>Tgl: {{ now()->format('d/m/Y H:i') }}</p>
                                <p>--------------------------------</p>
                                @foreach($tagihan_aktif->detail as $item)
                                <div class="flex justify-between">
                                    <span>{{ $item->item }} x{{ $item->jumlah }}</span>
                                    <span>{{ number_format($item->subtotal) }}</span>
                                </div>
                                @endforeach
                                <p>--------------------------------</p>
                                <div class="flex justify-between font-bold text-lg">
                                    <span>TOTAL</span>
                                    <span>{{ number_format($tagihan_aktif->total_biaya) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Bayar</span>
                                    <span>{{ number_format($tagihan_aktif->jumlah_bayar) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Kembali</span>
                                    <span>{{ number_format($tagihan_aktif->kembalian) }}</span>
                                </div>
                                <p class="mt-4 text-center">Terima Kasih<br>Semoga Lekas Sembuh</p>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-12 text-center text-gray-400">
                    Pilih pasien untuk memproses pembayaran.
                </div>
            @endif
        </div>
    </div>
</div>
