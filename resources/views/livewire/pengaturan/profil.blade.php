<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Pengaturan Profil</h1>
        <p class="text-slate-500">Kelola informasi akun dan keamanan Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Edit Profil -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-2">Informasi Dasar</h3>
                
                @if(session('sukses_profil'))
                    <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm">{{ session('sukses_profil') }}</div>
                @endif

                <form wire:submit="simpanProfil" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="nama_lengkap" class="w-full rounded-lg border-slate-300">
                            @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-lg border-slate-300">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" wire:model="no_telepon" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                        <textarea wire:model="alamat" rows="3" class="w-full rounded-lg border-slate-300"></textarea>
                    </div>
                    <div class="pt-4 text-right">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ganti Password -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-2">Keamanan</h3>

                @if(session('sukses_sandi'))
                    <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm">{{ session('sukses_sandi') }}</div>
                @endif

                <form wire:submit="gantiSandi" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Lama</label>
                        <input type="password" wire:model="sandi_lama" class="w-full rounded-lg border-slate-300">
                        @error('sandi_lama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Baru</label>
                        <input type="password" wire:model="sandi_baru" class="w-full rounded-lg border-slate-300">
                        @error('sandi_baru') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Sandi Baru</label>
                        <input type="password" wire:model="konfirmasi_sandi" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            Ganti Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>