<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Fasilitas Puskesmas</h1>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow transition">
            <span>+</span> Tambah Fasilitas
        </button>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($fasilitas as $item)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
            <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400 text-4xl">
                <!-- Placeholder Foto -->
                🏥
            </div>
            <div class="p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $item->nama_fasilitas }}</h3>
                <p class="text-gray-500 text-sm mb-4 line-clamp-3">{{ $item->deskripsi }}</p>
                
                <div class="flex gap-2 border-t pt-4 border-gray-50">
                    <button wire:click="edit({{ $item->id }})" class="flex-1 text-blue-600 hover:bg-blue-50 py-2 rounded text-sm font-medium">Edit</button>
                    <button wire:click="hapus({{ $item->id }})" wire:confirm="Hapus fasilitas ini?" class="flex-1 text-red-600 hover:bg-red-50 py-2 rounded text-sm font-medium">Hapus</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">{{ $modeEdit ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h3>
            
            <form wire:submit="simpan" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
                    <input type="text" wire:model="nama_fasilitas" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    @error('nama_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi" rows="4" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
                    @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="$set('tampilkanModal', false)" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
