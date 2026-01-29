<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Jadwal Praktik Dokter</h1>
            <p class="text-slate-500">Atur jadwal layanan poli per hari.</p>
        </div>
        @auth
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jadwal
        </button>
        @endauth
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Poli</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Dokter</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Jam Praktik</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kuota</th>
                    @auth
                    <th class="px-6 py-3 text-right">Aksi</th>
                    @endauth
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($dataJadwal as $j)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $j->hari }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $j->poli->nama_poli }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-900">{{ $j->dokter->pengguna->nama_lengkap ?? 'Dokter Hapus' }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-slate-600">
                        {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $j->kuota_pasien }} Pasien</td>
                    @auth
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <button wire:click="edit({{ $j->id }})" class="text-emerald-600 hover:text-emerald-900 mr-2">Edit</button>
                        <button wire:click="hapus({{ $j->id }})" class="text-red-600 hover:text-red-900" onclick="confirm('Hapus jadwal ini?') || event.stopImmediatePropagation()">Hapus</button>
                    </td>
                    @endauth
                </tr>
                @empty
                <tr><td colspan="{{ Auth::check() ? 6 : 5 }}" class="px-6 py-8 text-center text-slate-400">Belum ada jadwal praktik.</td></tr>
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
                        <h3 class="text-lg font-medium text-slate-900 mb-4">{{ $modeEdit ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Hari Praktik</label>
                                <select wire:model="hari" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                        <option value="{{ $h }}">{{ $h }}</option>
                                    @endforeach
                                </select>
                                @error('hari') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Poli Layanan</label>
                                <select wire:model="id_poli" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    <option value="">-- Pilih Poli --</option>
                                    @foreach($poliList as $poli)
                                        <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                    @endforeach
                                </select>
                                @error('id_poli') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Dokter</label>
                                <select wire:model="id_dokter" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokterList as $dokter)
                                        <option value="{{ $dokter->id }}">{{ $dokter->pengguna->nama_lengkap }} ({{ $dokter->sip }})</option>
                                    @endforeach
                                </select>
                                @error('id_dokter') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Jam Mulai</label>
                                    <input type="time" wire:model="jam_mulai" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    @error('jam_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Jam Selesai</label>
                                    <input type="time" wire:model="jam_selesai" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    @error('jam_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Kuota Pasien</label>
                                <input type="number" wire:model="kuota_pasien" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                @error('kuota_pasien') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="aktif" class="h-4 w-4 text-emerald-600 border-slate-300 rounded">
                                <label class="ml-2 block text-sm text-slate-900">Jadwal Aktif</label>
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