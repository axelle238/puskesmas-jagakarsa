<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Master Data Poli</h1>
            <p class="text-slate-500">Kelola unit layanan dan integrasi klaster ILP.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Poli
        </button>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
        {{ session('sukses') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nama Poli</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Klaster ILP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($dataPoli as $poli)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-mono text-sm font-bold text-slate-700">{{ $poli->kode_poli }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $poli->nama_poli }}</div>
                        <div class="text-xs text-slate-500">{{ Str::limit($poli->deskripsi, 30) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $poli->klaster->nama_klaster ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $poli->lokasi_ruangan }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $poli->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $poli->aktif ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <button wire:click="edit({{ $poli->id }})" class="text-emerald-600 hover:text-emerald-900 mr-2">Edit</button>
                        <button wire:click="hapus({{ $poli->id }})" class="text-red-600 hover:text-red-900" onclick="confirm('Hapus poli ini?') || event.stopImmediatePropagation()">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada data poli.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-medium text-slate-900 mb-4">{{ $modeEdit ? 'Edit Poli' : 'Tambah Poli Baru' }}</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Kode Poli</label>
                                <input type="text" wire:model="kode_poli" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" placeholder="Contoh: P-UMUM">
                                @error('kode_poli') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Nama Poli</label>
                                <input type="text" wire:model="nama_poli" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                @error('nama_poli') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Klaster ILP</label>
                                <select wire:model="id_klaster" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    <option value="">-- Pilih Klaster --</option>
                                    @foreach($klasterList as $klaster)
                                        <option value="{{ $klaster->id }}">{{ $klaster->nama_klaster }}</option>
                                    @endforeach
                                </select>
                                @error('id_klaster') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Lokasi Ruangan</label>
                                <input type="text" wire:model="lokasi_ruangan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Deskripsi</label>
                                <textarea wire:model="deskripsi" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="aktif" class="h-4 w-4 text-emerald-600 border-slate-300 rounded">
                                <label class="ml-2 block text-sm text-slate-900">Aktif / Beroperasi</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>