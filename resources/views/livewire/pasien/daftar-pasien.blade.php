<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Pasien</h1>
            <p class="text-slate-500">Kelola data rekam medis dan informasi pasien.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Pasien Baru
        </button>
    </div>

    <!-- Flash Message -->
    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('sukses') }}
    </div>
    @endif

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Search Bar -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="cari" type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" placeholder="Cari Nama, NIK, atau No. RM...">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No. RM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Identitas Pasien</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis Kelamin</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Usia</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($dataPasien as $pasien)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600">
                            {{ $pasien->no_rekam_medis }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-900">{{ $pasien->nama_lengkap }}</div>
                            <div class="text-sm text-slate-500">NIK: {{ $pasien->nik }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pasien->no_bpjs)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">BPJS</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">Umum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="bukaModalAntrian({{ $pasien->id }})" class="text-blue-600 hover:text-blue-900 mr-3 font-bold">Daftar Antrian</button>
                            <button wire:click="edit({{ $pasien->id }})" class="text-emerald-600 hover:text-emerald-900 mr-3">Edit</button>
                            <button wire:click="hapus({{ $pasien->id }})" class="text-red-600 hover:text-red-900" onclick="confirm('Yakin ingin menghapus pasien ini?') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2 text-sm">Tidak ada data pasien ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 sm:px-6">
            {{ $dataPasien->links() }}
        </div>
    </div>

    <!-- MODAL ANTRIAN -->
    @if($tampilkanModalAntrian)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="tutupModalAntrian"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpanAntrian">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4 border-b border-slate-100 pb-2">
                            Daftarkan Pasien ke Poli
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Poli</label>
                                <select wire:model.live="pilihPoli" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    <option value="">-- Pilih Poli --</option>
                                    @foreach($daftarPoli as $poli)
                                        <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                    @endforeach
                                </select>
                                @error('pilihPoli') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            @if($pilihPoli)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Dokter (Jadwal Hari Ini)</label>
                                @if(count($jadwalTersedia) > 0)
                                    <select wire:model="pilihDokter" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        <option value="">-- Pilih Dokter --</option>
                                        @foreach($jadwalTersedia as $jadwal)
                                            <option value="{{ $jadwal->id }}">
                                                {{ $jadwal->dokter->pengguna->nama_lengkap }} ({{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pilihDokter') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @else
                                    <p class="text-sm text-red-500 bg-red-50 p-3 rounded-lg">Tidak ada jadwal dokter untuk poli ini hari ini.</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm" @if(empty($jadwalTersedia)) disabled @endif>
                            Daftarkan
                        </button>
                        <button type="button" wire:click="tutupModalAntrian" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL FORM -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="tutupModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900 mb-6 border-b border-slate-100 pb-2" id="modal-title">
                                    {{ $modeEdit ? 'Edit Data Pasien' : 'Tambah Pasien Baru' }}
                                </h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- No RM (Readonly) -->
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">No. Rekam Medis</label>
                                        <input type="text" wire:model="no_rekam_medis" disabled class="bg-slate-100 text-slate-500 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>

                                    <!-- NIK -->
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">NIK (KTP)</label>
                                        <input type="text" wire:model="nik" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" maxlength="16">
                                        @error('nik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Nama Lengkap -->
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                                        <input type="text" wire:model="nama_lengkap" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- TTL -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Tempat Lahir</label>
                                        <input type="text" wire:model="tempat_lahir" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                                        <input type="date" wire:model="tanggal_lahir" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        @error('tanggal_lahir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                                        <select wire:model="jenis_kelamin" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>

                                    <!-- No HP -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">No. Telepon / WA</label>
                                        <input type="text" wire:model="no_telepon" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>

                                    <!-- Alamat -->
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                                        <textarea wire:model="alamat_lengkap" rows="2" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                                        @error('alamat_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <!-- No BPJS -->
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor BPJS (Opsional)</label>
                                        <input type="text" wire:model="no_bpjs" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" placeholder="Kosongkan jika pasien umum">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span wire:loading.remove wire:target="simpan">Simpan Data</span>
                            <span wire:loading wire:target="simpan">Menyimpan...</span>
                        </button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>