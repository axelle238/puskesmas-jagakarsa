<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Perencanaan Tingkat Puskesmas (PTP)</h1>
            <p class="text-slate-500">Kelola RUK, RPK, dan Monitoring Realisasi Anggaran.</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="tahun" class="rounded-lg border-slate-300 text-sm font-bold">
                @for($i = date('Y')+1; $i >= 2024; $i--)
                    <option value="{{ $i }}">Tahun {{ $i }}</option>
                @endfor
            </select>
            <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kegiatan
            </button>
        </div>
    </div>

    @if(session('sukses'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('sukses') }}
        </div>
    @endif

    <!-- Dashboard Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Total Pagu Usulan (RUK)</h3>
            <p class="text-2xl font-black text-slate-900">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Anggaran Disetujui (RPK)</h3>
            <p class="text-2xl font-black text-blue-600">Rp {{ number_format($stats['total_disetujui'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Realisasi (Serapan)</h3>
            <div class="flex items-end gap-2">
                <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($stats['total_realisasi'], 0, ',', '.') }}</p>
                @if($stats['total_disetujui'] > 0)
                    <span class="mb-1 text-xs font-bold {{ ($stats['total_realisasi'] / $stats['total_disetujui']) * 100 > 90 ? 'text-red-500' : 'text-slate-400' }}">
                        ({{ round(($stats['total_realisasi'] / $stats['total_disetujui']) * 100, 1) }}%)
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row justify-between gap-4">
            <div class="flex gap-2">
                <select wire:model.live="sumber_dana" class="rounded-lg border-slate-300 text-sm w-full md:w-auto">
                    <option value="">Semua Sumber Dana</option>
                    <option value="APBD">APBD</option>
                    <option value="BOK">BOK (Bantuan Operasional Kesehatan)</option>
                    <option value="JKN">JKN (Kapitasi)</option>
                    <option value="BLUD">BLUD</option>
                </select>
            </div>
            <input type="text" wire:model.live.debounce.300ms="cari" placeholder="Cari nama kegiatan..." class="rounded-lg border-slate-300 text-sm w-full md:w-64">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kegiatan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sumber Dana</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pagu (RUK)</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Disetujui (RPK)</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Realisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($kegiatan as $k)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900">{{ $k->nama_kegiatan }}</p>
                            <p class="text-xs text-slate-500 mt-1">PJ: {{ $k->penanggungJawab->nama_lengkap ?? '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $k->waktu_pelaksanaan }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-bold rounded bg-slate-100 text-slate-600">{{ $k->sumber_dana }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-mono">
                            Rp {{ number_format($k->pagu_anggaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-blue-600 font-mono">
                            @if($k->anggaran_disetujui)
                                Rp {{ number_format($k->anggaran_disetujui, 0, ',', '.') }}
                            @else
                                <span class="text-slate-400 font-normal italic">Belum disetujui</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php $persen = $k->persentase_serapan; @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full {{ $persen > 90 ? 'bg-red-500' : 'bg-emerald-500' }}" style="width: {{ $persen }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-600">{{ $persen }}%</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1 font-mono">Sisa: Rp {{ number_format($k->sisa_anggaran, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badges = [
                                    'usulan' => 'bg-yellow-100 text-yellow-800',
                                    'disetujui' => 'bg-blue-100 text-blue-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'berjalan' => 'bg-purple-100 text-purple-800',
                                    'selesai' => 'bg-emerald-100 text-emerald-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badges[$k->status] }}">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <button wire:click="kelolaRealisasi({{ $k->id }})" class="text-emerald-600 hover:text-emerald-900 font-bold" title="Kelola Realisasi Anggaran">
                                Realisasi
                            </button>
                            <button wire:click="edit({{ $k->id }})" class="text-blue-600 hover:text-blue-900 font-bold">Edit</button>
                            <button wire:click="hapus({{ $k->id }})" class="text-red-600 hover:text-red-900 font-bold" onclick="confirm('Yakin hapus kegiatan ini?') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                            Tidak ada kegiatan untuk tahun {{ $tahun }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $kegiatan->links() }}
        </div>
    </div>

    <!-- MODAL KEGIATAN -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-bold text-slate-900">{{ $modeEdit ? 'Edit Kegiatan' : 'Tambah Kegiatan Baru' }}</h3>
                            <button type="button" wire:click="tutupModal" class="text-slate-400 hover:text-slate-600">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Kegiatan</label>
                                <input type="text" wire:model="nama_kegiatan" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                                @error('nama_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Sumber Dana</label>
                                <select wire:model="sumber_dana_input" class="w-full rounded-lg border-slate-300">
                                    <option value="APBD">APBD</option>
                                    <option value="BOK">BOK</option>
                                    <option value="JKN">JKN</option>
                                    <option value="BLUD">BLUD</option>
                                    <option value="LAINNYA">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Status</label>
                                <select wire:model="status" class="w-full rounded-lg border-slate-300">
                                    <option value="usulan">Usulan (RUK)</option>
                                    <option value="disetujui">Disetujui (RPK)</option>
                                    <option value="berjalan">Sedang Berjalan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Pagu Anggaran (Usulan)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-slate-500">Rp</span>
                                    <input type="number" wire:model="pagu_anggaran" class="w-full pl-10 rounded-lg border-slate-300">
                                </div>
                                @error('pagu_anggaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Anggaran Disetujui (Fix)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-slate-500">Rp</span>
                                    <input type="number" wire:model="anggaran_disetujui" class="w-full pl-10 rounded-lg border-slate-300">
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-1">Indikator Keluaran & Target</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" wire:model="sasaran" placeholder="Sasaran (Misal: Ibu Hamil)" class="w-full rounded-lg border-slate-300">
                                    <input type="text" wire:model="target_kinerja" placeholder="Target (Misal: 100 Orang)" class="w-full rounded-lg border-slate-300">
                                </div>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-1">Penanggung Jawab</label>
                                <select wire:model="penanggung_jawab_id" class="w-full rounded-lg border-slate-300">
                                    <option value="">Pilih Pegawai</option>
                                    @foreach($pegawaiList as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_lengkap }} ({{ $p->jabatan }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Data
                        </button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:text-slate-500 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL REALISASI -->
    @if($tampilkanModalRealisasi && $kegiatanTerpilih)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                
                <div class="bg-slate-50 px-4 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Kelola Realisasi Anggaran</h3>
                        <p class="text-sm text-slate-500">{{ $kegiatanTerpilih->nama_kegiatan }}</p>
                    </div>
                    <button wire:click="tutupModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 h-[600px]">
                    <!-- Kiri: Form Input -->
                    <div class="p-6 border-r border-slate-200 overflow-y-auto bg-white">
                        <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">Input Realisasi Baru</h4>
                        
                        @if(session('sukses_realisasi'))
                            <div class="mb-4 p-2 bg-emerald-50 text-emerald-700 text-xs rounded border border-emerald-200">{{ session('sukses_realisasi') }}</div>
                        @endif

                        <form wire:submit="simpanRealisasi" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Tanggal</label>
                                <input type="date" wire:model="tanggal_realisasi" class="w-full rounded border-slate-300 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Jumlah (Rp)</label>
                                <input type="number" wire:model="jumlah_realisasi" class="w-full rounded border-slate-300 text-sm font-mono">
                                <p class="text-xs text-slate-400 mt-1">Sisa Anggaran: Rp {{ number_format($kegiatanTerpilih->sisa_anggaran, 0, ',', '.') }}</p>
                                @error('jumlah_realisasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Uraian Pengeluaran</label>
                                <textarea wire:model="uraian_realisasi" rows="3" class="w-full rounded border-slate-300 text-sm" placeholder="Contoh: Pembelian Snack Rapat..."></textarea>
                                @error('uraian_realisasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Bukti Dokumen (Opsional)</label>
                                <input type="file" wire:model="bukti_dokumen" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>

                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm">
                                Simpan Realisasi
                            </button>
                        </form>
                    </div>

                    <!-- Kanan: History -->
                    <div class="col-span-2 p-6 overflow-y-auto bg-slate-50">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Riwayat Penggunaan Dana</h4>
                            <span class="text-sm font-mono bg-white px-2 py-1 rounded border">Total: Rp {{ number_format($kegiatanTerpilih->realisasi->sum('jumlah'), 0, ',', '.') }}</span>
                        </div>

                        <div class="space-y-3">
                            @forelse($kegiatanTerpilih->realisasi as $r)
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-slate-200 flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $r->uraian_pengeluaran }}</p>
                                    <p class="text-xs text-slate-500">{{ $r->tanggal_realisasi->format('d M Y') }} • Input: {{ $r->penginput->nama_lengkap ?? 'System' }}</p>
                                    @if($r->bukti_dokumen)
                                        <a href="{{ asset('storage/' . $r->bukti_dokumen) }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            Lihat Bukti
                                        </a>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-mono font-bold text-slate-800">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</p>
                                    <button wire:click="hapusRealisasi({{ $r->id }})" class="text-xs text-red-500 hover:underline mt-1">Hapus</button>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-10 text-slate-400">Belum ada data realisasi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
