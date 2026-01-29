<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Stok Obat & Farmasi</h1>
            <p class="text-slate-500">Kelola persediaan obat, harga, dan tanggal kedaluwarsa.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 shadow-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Obat
        </button>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('sukses') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Search -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="cari" type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" placeholder="Cari Kode atau Nama Obat...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nama Obat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kedaluwarsa</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($dataObat as $obat)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-slate-600">{{ $obat->kode_obat }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-900">{{ $obat->nama_obat }}</div>
                            <div class="text-xs text-slate-400">{{ $obat->satuan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $obat->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($obat->stok_saat_ini <= $obat->stok_minimum)
                                <span class="text-red-600 font-bold flex items-center gap-1">
                                    {{ $obat->stok_saat_ini }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </span>
                            @else
                                <span class="text-emerald-700 font-medium">{{ $obat->stok_saat_ini }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            Rp {{ number_format($obat->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($obat->tanggal_kedaluwarsa < now()->addMonths(3))
                                <span class="text-red-600 font-medium">{{ $obat->tanggal_kedaluwarsa->format('d/m/Y') }}</span>
                            @else
                                <span class="text-slate-600">{{ $obat->tanggal_kedaluwarsa->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $obat->id }})" class="text-emerald-600 hover:text-emerald-900 mr-3">Edit</button>
                            <button wire:click="hapus({{ $obat->id }})" class="text-red-600 hover:text-red-900" onclick="confirm('Hapus obat ini?') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada data obat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 sm:px-6">
            {{ $dataObat->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-medium text-slate-900 mb-4">{{ $modeEdit ? 'Edit Data Obat' : 'Tambah Obat Baru' }}</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Kode Obat</label>
                                <input type="text" wire:model="kode_obat" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                @error('kode_obat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Nama Obat</label>
                                <input type="text" wire:model="nama_obat" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                @error('nama_obat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Kategori</label>
                                    <select wire:model="kategori" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="Obat Bebas">Obat Bebas</option>
                                        <option value="Obat Keras">Obat Keras</option>
                                        <option value="Obat Luar">Obat Luar</option>
                                        <option value="Alat Kesehatan">Alat Kesehatan</option>
                                    </select>
                                    @error('kategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Satuan</label>
                                    <input type="text" wire:model="satuan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" placeholder="Tablet/Botol">
                                    @error('satuan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Stok Saat Ini</label>
                                    <input type="number" wire:model="stok_saat_ini" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Stok Minimum</label>
                                    <input type="number" wire:model="stok_minimum" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Harga Satuan (Rp)</label>
                                    <input type="number" wire:model="harga_satuan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Tanggal Kedaluwarsa</label>
                                    <input type="date" wire:model="tanggal_kedaluwarsa" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>