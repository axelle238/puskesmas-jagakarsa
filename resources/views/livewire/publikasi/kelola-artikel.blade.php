<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Publikasi Artikel</h1>
            <p class="text-slate-500">Kelola konten edukasi kesehatan untuk masyarakat.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tulis Artikel
        </button>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
        {{ session('sukses') }}
    </div>
    @endif

    <!-- Daftar Artikel -->
    <div class="space-y-4">
        @forelse($dataArtikel as $a)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-start">
            <!-- Thumbnail -->
            <div class="w-full md:w-48 h-32 bg-slate-200 rounded-lg overflow-hidden flex-shrink-0">
                @if($a->gambar_sampul)
                    <img src="{{ asset('storage/' . $a->gambar_sampul) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>
            
            <!-- Content -->
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-bold">{{ $a->kategori }}</span>
                    @if($a->publikasi)
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-bold">Terbit</span>
                    @else
                        <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-bold">Draft</span>
                    @endif
                    <span class="text-xs text-slate-400">{{ $a->created_at->format('d M Y') }}</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $a->judul }}</h3>
                <p class="text-sm text-slate-600 line-clamp-2 mb-3">{{ $a->ringkasan }}</p>
                <div class="flex gap-3">
                    <button wire:click="edit({{ $a->id }})" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">Edit</button>
                    <button wire:click="hapus({{ $a->id }})" class="text-sm font-medium text-red-600 hover:text-red-800" onclick="confirm('Hapus artikel ini?') || event.stopImmediatePropagation()">Hapus</button>
                    <a href="{{ route('publik.artikel.baca', $a->slug) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat</a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-slate-400 bg-white rounded-xl border border-dashed border-slate-300">
            Belum ada artikel.
        </div>
        @endforelse

        {{ $dataArtikel->links() }}
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">{{ $modeEdit ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul Artikel</label>
                                    <input type="text" wire:model="judul" class="w-full rounded-lg border-slate-300">
                                    @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Ringkasan (Intro)</label>
                                    <textarea wire:model="ringkasan" rows="3" class="w-full rounded-lg border-slate-300"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Isi Konten</label>
                                    <textarea wire:model="konten" rows="10" class="w-full rounded-lg border-slate-300 font-mono text-sm"></textarea>
                                    @error('konten') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                                    <select wire:model="kategori" class="w-full rounded-lg border-slate-300">
                                        <option value="Umum">Umum</option>
                                        <option value="Ibu & Anak">Ibu & Anak</option>
                                        <option value="Penyakit Menular">Penyakit Menular</option>
                                        <option value="Gizi">Gizi</option>
                                        <option value="Lansia">Lansia</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Gambar Sampul</label>
                                    @if($gambar)
                                        <img src="{{ $gambar->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg mb-2">
                                    @elseif($gambar_lama)
                                        <img src="{{ asset('storage/' . $gambar_lama) }}" class="w-full h-32 object-cover rounded-lg mb-2">
                                    @endif
                                    <input type="file" wire:model="gambar" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                    @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="publikasi" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-2 text-sm text-slate-900">Terbitkan Langsung</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Artikel
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