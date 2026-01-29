<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Register Risiko</h1>
            <p class="text-slate-500">Identifikasi dan pengendalian risiko operasional di setiap unit.</p>
        </div>
        <button wire:click="tambah" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Identifikasi Risiko Baru
        </button>
    </div>

    @if(session('sukses'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            {{ session('sukses') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Unit & Risiko</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Penyebab & Dampak</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Skor Risiko</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Rencana Penanganan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($daftarRisiko as $r)
                <tr>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 text-xs font-bold rounded mb-1">{{ $r->unit_kerja }}</span>
                        <p class="text-sm font-bold text-slate-900">{{ $r->pernyataan_risiko }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <p><span class="font-bold text-xs text-slate-400 uppercase">Sebab:</span> {{ $r->penyebab }}</p>
                        <p class="mt-1"><span class="font-bold text-xs text-slate-400 uppercase">Akibat:</span> {{ $r->dampak }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $color = match($r->tingkat_risiko) {
                                'Ekstrem' => 'bg-red-600',
                                'Tinggi' => 'bg-orange-500',
                                'Sedang' => 'bg-yellow-400',
                                default => 'bg-green-500'
                            };
                        @endphp
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full text-white font-black text-lg shadow-sm {{ $color }}">
                            {{ $r->nilai_kemungkinan * $r->nilai_dampak }}
                        </span>
                        <p class="text-[10px] font-bold uppercase mt-1 text-slate-500">{{ $r->tingkat_risiko }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $r->rencana_penanganan }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400">Belum ada data risiko.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">{{ $daftarRisiko->links() }}</div>
    </div>

    <!-- MODAL INPUT -->
    @if($modalInput)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900 bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl p-6">
            <h3 class="font-bold text-lg mb-4 text-slate-900">Identifikasi Risiko Baru</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1">Unit Kerja</label>
                    <input type="text" wire:model="unit_kerja" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Farmasi">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Pernyataan Risiko (Apa yang mungkin terjadi?)</label>
                    <textarea wire:model="pernyataan_risiko" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Penyebab</label>
                        <textarea wire:model="penyebab" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Dampak</label>
                        <textarea wire:model="dampak" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                    </div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Kemungkinan (1-5)</label>
                        <input type="range" wire:model.live="nilai_kemungkinan" min="1" max="5" class="w-full">
                        <div class="flex justify-between text-xs text-slate-400 font-bold mt-1"><span>Jarang</span><span>Sering</span></div>
                        <p class="text-center font-bold text-blue-600 mt-1">{{ $nilai_kemungkinan }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Dampak (1-5)</label>
                        <input type="range" wire:model.live="nilai_dampak" min="1" max="5" class="w-full">
                        <div class="flex justify-between text-xs text-slate-400 font-bold mt-1"><span>Ringan</span><span>Bencana</span></div>
                        <p class="text-center font-bold text-red-600 mt-1">{{ $nilai_dampak }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Pengendalian Saat Ini</label>
                    <input type="text" wire:model="pengendalian_saat_ini" class="w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Rencana Penanganan Tambahan</label>
                    <textarea wire:model="rencana_penanganan" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                </div>
                
                <button wire:click="simpan" class="w-full bg-slate-900 text-white py-2 rounded-lg font-bold mt-2">Simpan ke Register</button>
                <button wire:click="$set('modalInput', false)" class="w-full text-slate-500 py-2 text-sm">Batal</button>
            </div>
        </div>
    </div>
    @endif
</div>
