<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Master Tarif & Tindakan</h1>
            <p class="text-slate-500">Kelola daftar layanan medis dan harga.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Tindakan
        </button>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
        {{ session('sukses') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <input wire:model.live.debounce.300ms="cari" type="text" class="w-full md:w-1/3 rounded-lg border-slate-300 text-sm" placeholder="Cari Tindakan...">
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Tindakan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Poli</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Tarif (Rp)</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($dataTindakan as $t)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-mono text-sm font-bold text-slate-600">{{ $t->kode_tindakan }}</td>
                        <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $t->nama_tindakan }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $t->poli->nama_poli }}</td>
                        <td class="px-6 py-4 text-sm text-right font-bold text-emerald-700">{{ number_format($t->tarif, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($t->aktif)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-bold">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="edit({{ $t->id }})" class="text-emerald-600 hover:text-emerald-800 font-bold text-sm mr-3">Edit</button>
                            <button wire:click="hapus({{ $t->id }})" class="text-red-600 hover:text-red-800 font-bold text-sm">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data tindakan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $dataTindakan->links() }}
        </div>
    </div>

    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">{{ $modeEdit ? 'Edit Tindakan' : 'Tambah Tindakan' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kode Tindakan</label>
                                <input type="text" wire:model="kode_tindakan" class="w-full rounded-lg border-slate-300 uppercase" placeholder="TM-XXX">
                                @error('kode_tindakan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Tindakan</label>
                                <input type="text" wire:model="nama_tindakan" class="w-full rounded-lg border-slate-300">
                                @error('nama_tindakan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Poli</label>
                                <select wire:model="id_poli" class="w-full rounded-lg border-slate-300">
                                    <option value="">-- Pilih Poli --</option>
                                    @foreach($poliList as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                                    @endforeach
                                </select>
                                @error('id_poli') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tarif (Rp)</label>
                                <input type="number" wire:model="tarif" class="w-full rounded-lg border-slate-300">
                                @error('tarif') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" wire:model="aktif" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-slate-700">Aktif</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
