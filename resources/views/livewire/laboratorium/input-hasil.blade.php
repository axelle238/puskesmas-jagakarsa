<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Laboratorium</h1>
            <p class="text-slate-500">Antrian pemeriksaan dan input hasil laboratorium.</p>
        </div>
        <div class="flex gap-2">
            <!-- Tombol Dummy untuk Testing -->
            <button wire:click="buatDummy" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg text-sm">
                + Dummy Data
            </button>
        </div>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('sukses') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Search -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <input wire:model.live.debounce.300ms="cari" type="text" class="w-full md:w-1/3 rounded-lg border-slate-300 text-sm" placeholder="Cari No. Permintaan...">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">No. Req</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Dokter Pengirim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Permintaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($daftarPermintaan as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-slate-700">{{ $p->no_permintaan }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-900">{{ $p->rekamMedis->pasien->nama_lengkap ?? 'Umum' }}</div>
                            <div class="text-xs text-slate-500">{{ $p->waktu_permintaan->format('d M H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $p->dokter->pengguna->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-800">{{ $p->catatan_permintaan }}</td>
                        <td class="px-6 py-4">
                            @if($p->status == 'menunggu')
                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-bold">Menunggu</span>
                            @elseif($p->status == 'selesai')
                                <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-bold">Selesai</span>
                            @else
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-bold">Proses</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="pilihPermintaan({{ $p->id }})" class="text-emerald-600 hover:text-emerald-800 font-bold text-sm">
                                {{ $p->status == 'selesai' ? 'Lihat Hasil' : 'Input Hasil' }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada permintaan lab.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL INPUT HASIL -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Input Hasil Laboratorium</h3>
                        <button wire:click="tutupModal" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Info Pasien -->
                    <div class="bg-slate-50 p-4 rounded-lg mb-6 border border-slate-200">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="block text-slate-500 text-xs">No. Permintaan</span>
                                <span class="font-bold text-slate-800">{{ $permintaanTerpilih->no_permintaan }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 text-xs">Pasien</span>
                                <span class="font-bold text-slate-800">{{ $permintaanTerpilih->rekamMedis->pasien->nama_lengkap ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 text-xs">Dokter</span>
                                <span class="font-bold text-slate-800">{{ $permintaanTerpilih->dokter->pengguna->nama_lengkap ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 text-xs">Permintaan</span>
                                <span class="font-bold text-slate-800">{{ $permintaanTerpilih->catatan_permintaan }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Input Parameter -->
                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-slate-700 mb-2">Tambah Parameter Pemeriksaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end bg-slate-50 p-3 rounded-lg border border-slate-200">
                            <div class="md:col-span-2">
                                <label class="text-xs text-slate-500">Parameter</label>
                                <input type="text" wire:model="parameter" class="w-full rounded border-slate-300 text-sm" placeholder="Contoh: Hemoglobin">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Hasil</label>
                                <input type="text" wire:model="nilai_hasil" class="w-full rounded border-slate-300 text-sm" placeholder="12.5">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Nilai Rujukan</label>
                                <input type="text" wire:model="nilai_rujukan" class="w-full rounded border-slate-300 text-sm" placeholder="12-16">
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    <label class="text-xs text-slate-500">Satuan</label>
                                    <input type="text" wire:model="satuan" class="w-full rounded border-slate-300 text-sm" placeholder="g/dL">
                                </div>
                                <button wire:click="tambahParameter" class="bg-blue-600 hover:bg-blue-700 text-white rounded px-3 py-2 h-[38px] mt-auto">
                                    +
                                </button>
                            </div>
                        </div>
                        @error('parameter') <span class="text-red-500 text-xs block mt-1">Parameter wajib diisi</span> @enderror
                        @error('nilai_hasil') <span class="text-red-500 text-xs block mt-1">Nilai hasil wajib diisi</span> @enderror
                    </div>

                    <!-- Tabel Hasil -->
                    <div class="border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-600">Parameter</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-600">Hasil</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-600">Nilai Rujukan</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-600">Satuan</th>
                                    <th class="px-4 py-2 text-right"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($hasilList as $index => $h)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-slate-800">{{ $h['parameter'] }}</td>
                                    <td class="px-4 py-2 text-sm font-bold text-slate-900">{{ $h['nilai_hasil'] }}</td>
                                    <td class="px-4 py-2 text-sm text-slate-500">{{ $h['nilai_rujukan'] }}</td>
                                    <td class="px-4 py-2 text-sm text-slate-500">{{ $h['satuan'] }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <button wire:click="hapusParameter({{ $index }})" class="text-red-500 hover:text-red-700 text-xs font-bold">Hapus</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada hasil diinput.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="simpanHasil" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan & Selesai
                    </button>
                    <button wire:click="tutupModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>