<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Fasilitas Puskesmas</h1>
            <p class="text-slate-500">Kelola daftar fasilitas dan sarana prasarana.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Fasilitas
        </button>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
        {{ session('sukses') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($dataFasilitas as $f)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden group">
            <div class="h-48 bg-slate-200 overflow-hidden relative">
                @if($f->foto)
                    <img src="{{ asset('storage/' . $f->foto) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <button wire:click="edit({{ $f->id }})" class="bg-white text-slate-900 px-3 py-1.5 rounded-lg text-sm font-bold hover:bg-emerald-50">Edit</button>
                    <button wire:click="hapus({{ $f->id }})" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold hover:bg-red-700" onclick="confirm('Hapus fasilitas?') || event.stopImmediatePropagation()">Hapus</button>
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-slate-900 text-lg mb-1">{{ $f->nama_fasilitas }}</h3>
                <p class="text-sm text-slate-500 line-clamp-2">{{ $f->deskripsi }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-slate-400 bg-white rounded-xl border border-dashed border-slate-300">
            Belum ada data fasilitas.
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $dataFasilitas->links() }}
    </div>

    <!-- Modal -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">{{ $modeEdit ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Fasilitas</label>
                                <input type="text" wire:model="nama_fasilitas" class="w-full rounded-lg border-slate-300">
                                @error('nama_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                                <textarea wire:model="deskripsi" rows="3" class="w-full rounded-lg border-slate-300"></textarea>
                                @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Foto</label>
                                @if($foto)
                                    <img src="{{ $foto->temporaryUrl() }}" class="h-32 rounded-lg mb-2 object-cover">
                                @elseif($foto_lama)
                                    <img src="{{ asset('storage/' . $foto_lama) }}" class="h-32 rounded-lg mb-2 object-cover">
                                @endif
                                <input type="file" wire:model="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                @error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>