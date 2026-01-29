<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Artikel Edukasi Kesehatan</h1>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow transition">
            <span>+</span> Tulis Artikel
        </button>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Judul & Ringkasan</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($artikels as $artikel)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800 text-lg">{{ $artikel->judul }}</div>
                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $artikel->ringkasan }}</p>
                        <div class="text-xs text-gray-400 mt-2">Penulis: {{ $artikel->penulis->nama_lengkap }} • {{ $artikel->created_at->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold">{{ $artikel->kategori }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($artikel->publikasi)
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs font-bold">Terbit</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-bold">Draf</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="edit({{ $artikel->id }})" class="text-blue-600 hover:bg-blue-50 p-2 rounded mr-1">✏️</button>
                        <button wire:click="hapus({{ $artikel->id }})" wire:confirm="Hapus artikel ini?" class="text-red-600 hover:bg-red-50 p-2 rounded">🗑️</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada artikel.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $artikels->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <h3 class="font-bold text-lg text-gray-800">{{ $modeEdit ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h3>
                <button wire:click="$set('tampilkanModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <form wire:submit="simpan" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel</label>
                    <input type="text" wire:model="judul" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select wire:model="kategori" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                            <option value="Umum">Umum</option>
                            <option value="Ibu & Anak">Ibu & Anak</option>
                            <option value="Gizi">Gizi</option>
                            <option value="Lansia">Lansia</option>
                            <option value="Penyakit Menular">Penyakit Menular</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Publikasi</label>
                        <select wire:model="publikasi" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                            <option value="1">Terbit (Publik)</option>
                            <option value="0">Simpan sebagai Draf</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan (Intro)</label>
                    <textarea wire:model="ringkasan" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" placeholder="Ringkasan singkat untuk tampilan kartu..."></textarea>
                    @error('ringkasan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten Lengkap</label>
                    <textarea wire:model="konten" rows="10" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 font-mono text-sm" placeholder="Tulis isi artikel di sini..."></textarea>
                    @error('konten') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="$set('tampilkanModal', false)" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
