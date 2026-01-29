<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Kasir & Pembayaran</h1>
            <p class="text-slate-500">Proses tagihan pasien dan pembayaran akhir.</p>
        </div>
        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-bold">
            {{ $antrianBayar->count() }} Antrian Bayar
        </div>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('sukses') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Poli</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status Medis</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($antrianBayar as $rm)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-900">{{ $rm->pasien->nama_lengkap }}</div>
                            <div class="text-xs text-slate-500">{{ $rm->pasien->no_rekam_medis }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $rm->poli->nama_poli }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-bold">Siap Bayar</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="buatTagihan({{ $rm->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                Proses Tagihan
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada antrian pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PEMBAYARAN -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <div class="bg-slate-50 px-4 py-4 sm:px-6 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-slate-900">Rincian Tagihan</h3>
                    <div class="text-right">
                        <span class="block text-xs text-slate-500">Total Tagihan</span>
                        <span class="block text-xl font-black text-slate-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="px-4 py-4 sm:px-6">
                    <!-- Rincian -->
                    <div class="border rounded-lg overflow-hidden mb-6">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-600">Item</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-600">Jml</th>
                                    <th class="px-4 py-2 text-right text-xs font-bold text-slate-600">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach($detailTagihanList as $d)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-slate-800">
                                        {{ $d['item'] }}
                                        <span class="block text-xs text-slate-400">{{ $d['kategori'] }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-slate-600">{{ $d['jumlah'] }}</td>
                                    <td class="px-4 py-2 text-right text-sm font-medium text-slate-900">
                                        {{ number_format($d['subtotal'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Form Bayar -->
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Metode Pembayaran</label>
                                <select wire:model.live="metode_bayar" class="w-full rounded-lg border-slate-300">
                                    <option value="tunai">Tunai</option>
                                    <option value="qris">QRIS</option>
                                    <option value="debit">Kartu Debit</option>
                                    <option value="bpjs">BPJS (Gratis)</option>
                                </select>
                            </div>
                            
                            @if($metode_bayar != 'bpjs')
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Jumlah Uang Diterima</label>
                                <input type="number" wire:model.live="jumlah_bayar" class="w-full rounded-lg border-slate-300 font-mono font-bold text-right" placeholder="0">
                            </div>
                            @endif
                        </div>

                        @if($metode_bayar != 'bpjs')
                        <div class="mt-4 flex justify-between items-center border-t border-slate-200 pt-3">
                            <span class="font-bold text-slate-600">Kembalian:</span>
                            <span class="font-black text-xl {{ $kembalian < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                Rp {{ number_format($kembalian, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                    </div>

                    @if(session('error'))
                        <p class="text-red-500 text-sm mt-2 text-center font-bold">{{ session('error') }}</p>
                    @endif
                </div>

                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="prosesBayar" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-3 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                        {{ $metode_bayar == 'bpjs' ? 'Konfirmasi BPJS' : 'Bayar & Cetak Struk' }}
                    </button>
                    <button wire:click="tutupModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>