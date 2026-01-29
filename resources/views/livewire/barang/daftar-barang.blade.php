<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Inventaris & Aset</h1>
            <p class="text-slate-500">Kelola data barang non-medis, alat kesehatan, dan sarana prasarana.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 shadow-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Aset
        </button>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('sukses') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Filter & Search -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="cari" type="text" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Cari Nama Aset, Kode Barang...">
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="filterKategori" class="w-full rounded-lg border-slate-300 text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="filterKondisi" class="w-full rounded-lg border-slate-300 text-sm">
                    <option value="">Semua Kondisi</option>
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kondisi</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($dataBarang as $b)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-sm font-bold text-slate-600">{{ $b->kode_barang }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-900">{{ $b->nama_barang }}</div>
                            <div class="text-xs text-slate-500">{{ $b->merk }} {{ $b->nomor_seri ? '- SN: '.$b->nomor_seri : '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $b->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $b->lokasi_penyimpanan }}</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'baik' => 'bg-green-100 text-green-800',
                                    'rusak_ringan' => 'bg-yellow-100 text-yellow-800',
                                    'rusak_berat' => 'bg-red-100 text-red-800',
                                    'hilang' => 'bg-gray-100 text-gray-800',
                                    'dihapuskan' => 'bg-slate-200 text-slate-600',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold capitalize {{ $colors[$b->kondisi] ?? 'bg-slate-100' }}">
                                {{ str_replace('_', ' ', $b->kondisi) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button wire:click="edit({{ $b->id }})" class="text-emerald-600 hover:text-emerald-900 mr-3 font-bold">Edit</button>
                            <button wire:click="hapus({{ $b->id }})" class="text-red-600 hover:text-red-900 font-bold" onclick="confirm('Hapus aset ini?') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 sm:px-6">
            {{ $dataBarang->links() }}
        </div>
    </div>

    <!-- MODAL FORM -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">{{ $modeEdit ? 'Edit Data Aset' : 'Registrasi Aset Baru' }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kode Barang / Inventaris</label>
                                <input type="text" wire:model="kode_barang" class="w-full rounded-lg border-slate-300 font-mono uppercase" placeholder="INV-2024-XXX">
                                @error('kode_barang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                                <select wire:model="id_kategori" class="w-full rounded-lg border-slate-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach($kategoriList as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('id_kategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Barang</label>
                                <input type="text" wire:model="nama_barang" class="w-full rounded-lg border-slate-300">
                                @error('nama_barang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Merk / Brand</label>
                                <input type="text" wire:model="merk" class="w-full rounded-lg border-slate-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Seri</label>
                                <input type="text" wire:model="nomor_seri" class="w-full rounded-lg border-slate-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Penyimpanan</label>
                                <input type="text" wire:model="lokasi_penyimpanan" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Ruang Poli Umum">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi Saat Ini</label>
                                <select wire:model="kondisi" class="w-full rounded-lg border-slate-300">
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                    <option value="hilang">Hilang</option>
                                    <option value="dihapuskan">Dihapuskan (Afkir)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Perolehan</label>
                                <input type="number" wire:model="tahun_perolehan" class="w-full rounded-lg border-slate-300" placeholder="YYYY">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nilai Aset (Rp)</label>
                                <input type="number" wire:model="harga_perolehan" class="w-full rounded-lg border-slate-300">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Data
                        </button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
