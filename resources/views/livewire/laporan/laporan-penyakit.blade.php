<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Laporan Morbiditas (10 Besar Penyakit)</h1>
        <p class="text-slate-500">Rekapitulasi penyakit terbanyak berdasarkan diagnosa ICD-10.</p>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-6 flex gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Bulan</label>
            <select wire:model.live="bulan" class="rounded-lg border-slate-300 text-sm">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ sprintf('%02d', $i) }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Tahun</label>
            <select wire:model.live="tahun" class="rounded-lg border-slate-300 text-sm">
                @for($i=date('Y'); $i>=date('Y')-5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="ml-auto">
            <button onclick="window.print()" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / PDF
            </button>
        </div>
    </div>

    <!-- Chart / Visual (Placeholder) -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-6">
        <div class="h-4 bg-slate-100 rounded-full overflow-hidden flex">
            @foreach($laporan as $l)
                @php $width = ($l->total / ($laporan->sum('total') ?: 1)) * 100; @endphp
                <div class="h-full border-r border-white" style="width: {{ $width }}%; background-color: hsl({{ rand(0,360) }}, 70%, 50%);" title="{{ $l->asesmen }}"></div>
            @endforeach
        </div>
        <p class="text-xs text-center text-slate-400 mt-2">Distribusi Proporsi Kasus</p>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Peringkat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode ICD-10</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Diagnosa</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Jumlah Kasus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($laporan as $index => $l)
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-emerald-600 font-bold">{{ $l->diagnosis_kode }}</td>
                    <td class="px-6 py-4 text-sm text-slate-700">{{ $l->asesmen }}</td>
                    <td class="px-6 py-4 text-right text-sm font-black text-slate-900">{{ $l->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data diagnosa pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>