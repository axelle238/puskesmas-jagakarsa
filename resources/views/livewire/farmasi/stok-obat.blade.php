<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Stok Obat</h1>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow transition">
            <span>+</span> Tambah Obat
        </button>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <input wire:model.live.debounce.300ms="cari" type="text" placeholder="Cari nama atau kode obat..." class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Kode & Nama</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-center">Stok</th>
                    <th class="px-6 py-4">Harga / Exp</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($obats as $obat)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $obat->nama_obat }}</div>
                        <div class="text-xs text-gray-500 font-mono">{{ $obat->kode_obat }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs">{{ $obat->kategori ?? 'Umum' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-lg {{ $obat->stok_saat_ini <= $obat->stok_minimum ? 'text-red-600' : 'text-green-600' }}">
                            {{ $obat->stok_saat_ini }}
                        </span>
                        <div class="text-xs text-gray-400">{{ $obat->satuan }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800">Rp {{ number_format($obat->harga_satuan, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500">Exp: {{ $obat->tanggal_kedaluwarsa->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="edit({{ $obat->id }})" class="text-blue-600 hover:bg-blue-50 p-2 rounded mr-1">✏️</button>
                        <button wire:click="hapus({{ $obat->id }})" wire:confirm="Hapus data obat ini?" class="text-red-600 hover:bg-red-50 p-2 rounded">🗑️</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Data obat tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $obats->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="font-bold text-lg text-gray-800 mb-4">{{ $modeEdit ? 'Edit Obat' : 'Tambah Obat Baru' }}</h3>
            
            <form wire:submit="simpan" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Obat</label>
                        <input type="text" wire:model="kode_obat" class="w-full px-3 py-2 border rounded-lg" placeholder="ex: PAR-500">
                        @error('kode_obat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                        <input type="text" wire:model="nama_obat" class="w-full px-3 py-2 border rounded-lg" placeholder="ex: Paracetamol 500mg">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select wire:model="kategori" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Pilih Kategori</option>
                            <option value="Obat Bebas">Obat Bebas</option>
                            <option value="Obat Keras">Obat Keras</option>
                            <option value="Psikotropika">Psikotropika</option>
                            <option value="Alkes">Alat Kesehatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <input type="text" wire:model="satuan" class="w-full px-3 py-2 border rounded-lg" placeholder="Tablet/Botol/Strip">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini</label>
                        <input type="number" wire:model="stok_saat_ini" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                        <input type="number" wire:model="stok_minimum" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                        <input type="number" wire:model="harga_satuan" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kedaluwarsa</label>
                    <input type="date" wire:model="tanggal_kedaluwarsa" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" wire:click="$set('tampilkanModal', false)" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
