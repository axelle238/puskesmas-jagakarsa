<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Instansi</h1>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form wire:submit="simpan" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Identitas -->
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">Identitas Puskesmas</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Instansi</label>
                        <input type="text" wire:model="nama_instansi" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                        @error('nama_instansi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Resmi</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" wire:model="telepon" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea wire:model="alamat" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <!-- Visi Misi -->
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">Visi & Misi</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                        <textarea wire:model="visi" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                        <textarea wire:model="misi" rows="5" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
