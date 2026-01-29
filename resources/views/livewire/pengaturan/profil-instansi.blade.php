<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Pengaturan Instansi & CMS</h1>
        <p class="text-slate-500">Kelola profil Puskesmas dan konten halaman depan website.</p>
    </div>

    @if(session('pesan'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('pesan') }}
        </div>
    @endif

    <form wire:submit="simpan">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- KOLOM KIRI: Data Instansi -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Identitas Instansi</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Instansi</label>
                            <input type="text" wire:model="nama_instansi" class="w-full rounded-lg border-slate-300">
                            @error('nama_instansi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email Resmi</label>
                            <input type="email" wire:model="email" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                            <input type="text" wire:model="telepon" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                            <textarea wire:model="alamat" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Visi & Misi</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Visi</label>
                            <textarea wire:model="visi" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Misi</label>
                            <textarea wire:model="misi" rows="4" class="w-full rounded-lg border-slate-300"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: CMS Homepage -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Banner Utama (Hero)</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Hero (Headline)</label>
                            <input type="text" wire:model="hero_title" class="w-full rounded-lg border-slate-300" placeholder="Layanan Kesehatan...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Sub-Judul (Deskripsi)</label>
                            <textarea wire:model="hero_subtitle" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Gambar Background</label>
                            @if($hero_image)
                                <img src="{{ $hero_image->temporaryUrl() }}" class="h-32 rounded-lg mb-2 object-cover border border-slate-200">
                            @elseif($hero_image_lama)
                                <img src="{{ asset('storage/' . $hero_image_lama) }}" class="h-32 rounded-lg mb-2 object-cover border border-slate-200">
                            @endif
                            <input type="file" wire:model="hero_image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <p class="text-xs text-slate-400 mt-1">Disarankan ukuran 1920x800px.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Sambutan Kepala Puskesmas</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kepala Puskesmas</label>
                            <input type="text" wire:model="nama_kepala_puskesmas" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Isi Sambutan</label>
                            <textarea wire:model="sambutan_kepala" rows="4" class="w-full rounded-lg border-slate-300"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Foto Beliau</label>
                            @if($foto_kepala)
                                <img src="{{ $foto_kepala->temporaryUrl() }}" class="h-24 w-24 rounded-full mb-2 object-cover border border-slate-200">
                            @elseif($foto_kepala_lama)
                                <img src="{{ asset('storage/' . $foto_kepala_lama) }}" class="h-24 w-24 rounded-full mb-2 object-cover border border-slate-200">
                            @endif
                            <input type="file" wire:model="foto_kepala" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all transform hover:scale-105">
                Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>
