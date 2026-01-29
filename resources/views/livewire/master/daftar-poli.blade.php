<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Poli & Unit</h1>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow transition">
            <span>+</span> Tambah Poli
        </button>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($polis as $poli)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl font-bold">
                        {{ substr($poli->nama_poli, 0, 1) }}
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $poli->id }})" class="text-gray-400 hover:text-blue-600">✏️</button>
                        <button wire:click="hapus({{ $poli->id }})" wire:confirm="Hapus poli ini?" class="text-gray-400 hover:text-red-600">🗑️</button>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $poli->nama_poli }}</h3>
                
                @if($poli->klaster)
                    <span class="inline-block bg-purple-50 text-purple-700 text-xs px-2 py-0.5 rounded mb-2 font-medium border border-purple-100">
                        {{ $poli->klaster->nama_klaster }}
                    </span>
                @endif

                <p class="text-gray-500 text-sm mb-4">{{ $poli->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                <div class="flex items-center text-xs text-gray-400 gap-2 mb-4">
                    <span>📍 {{ $poli->lokasi_ruangan ?? '-' }}</span>
                    <span>•</span>
                    <span>{{ $poli->rekam_medis_count }} Kunjungan</span>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-50">
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Aktif</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">{{ $modeEdit ? 'Edit Poli' : 'Tambah Poli Baru' }}</h3>
            
            <form wire:submit="simpan" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Poli</label>
                    <input type="text" wire:model="nama_poli" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    @error('nama_poli') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Klaster ILP</label>
                    <select wire:model="id_klaster" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                        <option value="">-- Pilih Klaster --</option>
                        @foreach($klasters as $klaster)
                            <option value="{{ $klaster->id }}">{{ $klaster->nama_klaster }}</option>
                        @endforeach
                    </select>
                    @error('id_klaster') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Ruangan</label>
                    <input type="text" wire:model="lokasi_ruangan" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                    <textarea wire:model="deskripsi" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
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