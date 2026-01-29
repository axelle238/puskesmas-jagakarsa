<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Akun</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Edit Profil -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Data Diri</h2>
            
            @if (session()->has('pesan_profil'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ session('pesan_profil') }}
                </div>
            @endif

            <form wire:submit="simpanProfil" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" wire:model="nama_lengkap" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="email" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" wire:model="no_telepon" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea wire:model="alamat" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Ganti Password -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Keamanan</h2>

            @if (session()->has('pesan_sandi'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ session('pesan_sandi') }}
                </div>
            @endif

            <form wire:submit="gantiSandi" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Lama</label>
                    <input type="password" wire:model="sandi_lama" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    @error('sandi_lama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                    <input type="password" wire:model="sandi_baru" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    @error('sandi_baru') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi Baru</label>
                    <input type="password" wire:model="konfirmasi_sandi" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 rounded-lg transition">
                    Ganti Kata Sandi
                </button>
            </form>
        </div>

    </div>
</div>
