<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Insiden Keselamatan Pasien (IKP)</h1>
            <p class="text-slate-500">Pelaporan KTD, KNC, KTC, dan Sentinel secara digital.</p>
        </div>
        <button wire:click="lapor" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Lapor Insiden
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
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal & Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis & Grading</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi & Kronologi</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pelapor</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($laporan as $l)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $l->tanggal_kejadian->format('d/m/Y') }}<br>
                        <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($l->waktu_kejadian)->format('H:i') }} WIB</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-slate-100 text-slate-800">
                            {{ $l->jenis_insiden }}
                        </span>
                        <div class="mt-1">
                            @php
                                $colors = ['biru' => 'bg-blue-500', 'hijau' => 'bg-emerald-500', 'kuning' => 'bg-yellow-500', 'merah' => 'bg-red-500'];
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold text-white {{ $colors[$l->grading_risiko] }} uppercase">
                                {{ $l->grading_risiko }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-900">{{ $l->lokasi_kejadian }}</p>
                        <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $l->kronologi }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $l->pelapor->nama_lengkap ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        @if($l->status_investigasi == 'belum')
                            <span class="text-xs font-bold text-slate-400">Belum Diinvestigasi</span>
                        @else
                            <span class="text-xs font-bold text-blue-600">Dalam Proses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Nihil Insiden. Aman terkendali.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">{{ $laporan->links() }}</div>
    </div>

    <!-- MODAL LAPOR -->
    @if($modalLapor)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900 bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl p-6">
            <h3 class="font-bold text-lg mb-4 text-slate-900 border-b pb-2">Formulir Laporan Insiden</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-bold mb-1">Tanggal Kejadian</label>
                    <input type="date" wire:model="tanggal_kejadian" class="w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Waktu Kejadian</label>
                    <input type="time" wire:model="waktu_kejadian" class="w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Lokasi Kejadian</label>
                    <input type="text" wire:model="lokasi_kejadian" class="w-full rounded-lg border-slate-300" placeholder="Contoh: R. Tunggu Farmasi">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Jenis Insiden</label>
                    <select wire:model="jenis_insiden" class="w-full rounded-lg border-slate-300">
                        <option value="KPC">KPC (Potensial Cedera)</option>
                        <option value="KNC">KNC (Nyaris Cedera)</option>
                        <option value="KTC">KTC (Tidak Cedera)</option>
                        <option value="KTD">KTD (Tidak Diharapkan)</option>
                        <option value="Sentinel">Sentinel (Kematian/Cedera Serius)</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1">Kronologi Singkat</label>
                    <textarea wire:model="kronologi" rows="3" class="w-full rounded-lg border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Tindakan Segera yang Dilakukan</label>
                    <textarea wire:model="tindakan_segera" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Grading Risiko Awal (Matrix)</label>
                    <select wire:model="grading_risiko" class="w-full rounded-lg border-slate-300">
                        <option value="biru">Biru (Rendah)</option>
                        <option value="hijau">Hijau (Sedang)</option>
                        <option value="kuning">Kuning (Tinggi)</option>
                        <option value="merah">Merah (Ekstrem)</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button wire:click="$set('modalLapor', false)" class="px-4 py-2 text-slate-600 font-bold">Batal</button>
                <button wire:click="simpan" class="px-6 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700">Kirim Laporan</button>
            </div>
        </div>
    </div>
    @endif
</div>
