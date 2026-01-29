<div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Buku Kas Umum (BKU)</h1>
            <p class="text-slate-500">Rekapitulasi Penerimaan dan Pengeluaran Puskesmas.</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="bulan" class="rounded-lg border-slate-300 text-sm font-bold">
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                @endforeach
            </select>
            <select wire:model.live="tahun" class="rounded-lg border-slate-300 text-sm font-bold">
                @for($i = date('Y')+1; $i >= 2024; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <button class="bg-slate-900 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm" onclick="window.print()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100">
            <h3 class="text-emerald-800 font-bold text-xs uppercase tracking-wider mb-2">Total Penerimaan</h3>
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <p class="text-2xl font-black text-emerald-700">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            </div>
            <p class="text-xs text-emerald-600 mt-2">Dari Layanan Pasien (Kasir)</p>
        </div>
        <div class="bg-red-50 p-6 rounded-xl border border-red-100">
            <h3 class="text-red-800 font-bold text-xs uppercase tracking-wider mb-2">Total Pengeluaran</h3>
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                <p class="text-2xl font-black text-red-700">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
            </div>
            <p class="text-xs text-red-600 mt-2">Realisasi Anggaran Kegiatan</p>
        </div>
        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
            <h3 class="text-blue-800 font-bold text-xs uppercase tracking-wider mb-2">Saldo Akhir Bulan</h3>
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-2xl font-black text-blue-700">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
            </div>
            <p class="text-xs text-blue-600 mt-2">Selisih Masuk - Keluar</p>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Uraian Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Masuk (Debit)</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Keluar (Kredit)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($transaksi as $t)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-mono">
                            {{ \Carbon\Carbon::parse($t['tanggal'])->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900">{{ $t['keterangan'] }}</p>
                            <p class="text-xs text-slate-500">Ref ID: #{{ $t['ref_id'] }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $t['jenis'] == 'masuk' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $t['tipe_transaksi'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-mono text-emerald-600 font-bold">
                            @if($t['jenis'] == 'masuk')
                                Rp {{ number_format($t['jumlah'], 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-mono text-red-600 font-bold">
                            @if($t['jenis'] == 'keluar')
                                Rp {{ number_format($t['jumlah'], 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">Tidak ada transaksi bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 font-bold text-slate-900">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right uppercase text-xs tracking-wider">Total</td>
                        <td class="px-6 py-4 text-right font-mono text-emerald-700">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-mono text-red-700">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
