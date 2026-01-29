<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Data Pegawai & Akses</h1>
            <p class="text-slate-500">Manajemen SDM Puskesmas dan hak akses sistem.</p>
        </div>
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Pegawai
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
                <input wire:model.live.debounce.300ms="cari" type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" placeholder="Cari Nama, NIP, atau Jabatan...">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pegawai</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">NIP / Jabatan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Peran (Akses)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kontak</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($dataPegawai as $p)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                                    {{ substr($p->pengguna->nama_lengkap ?? 'X', 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $p->pengguna->nama_lengkap ?? '-' }}</div>
                                    <div class="text-sm text-slate-500">{{ $p->pengguna->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 font-medium">{{ $p->nip }}</div>
                            <div class="text-sm text-slate-500">{{ $p->jabatan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-100 text-purple-800',
                                    'dokter' => 'bg-emerald-100 text-emerald-800',
                                    'perawat' => 'bg-blue-100 text-blue-800',
                                    'apoteker' => 'bg-orange-100 text-orange-800',
                                    'pendaftaran' => 'bg-slate-100 text-slate-800',
                                ];
                                $role = $p->pengguna->peran ?? 'unknown';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColors[$role] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $p->pengguna->no_telepon ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $p->id }})" class="text-emerald-600 hover:text-emerald-900 mr-3">Edit</button>
                            <button wire:click="hapus({{ $p->id }})" class="text-red-600 hover:text-red-900" onclick="confirm('Yakin ingin menghapus pegawai ini? Akun login juga akan terhapus.') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            Belum ada data pegawai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 sm:px-6">
            {{ $dataPegawai->links() }}
        </div>
    </div>

    <!-- MODAL FORM -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900 mb-6 border-b border-slate-100 pb-2">
                                    {{ $modeEdit ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru' }}
                                </h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Informasi Dasar -->
                                    <div class="col-span-2">
                                        <h4 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wide">Informasi Pribadi</h4>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                                        <input type="text" wire:model="nama_lengkap" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Email (Untuk Login)</label>
                                        <input type="email" wire:model="email" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                                        <input type="text" wire:model="no_telepon" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">NIK (KTP)</label>
                                        <input type="text" wire:model="nik" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" maxlength="16">
                                        @error('nik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                                        <textarea wire:model="alamat" rows="2" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                                    </div>

                                    <!-- Informasi Kepegawaian -->
                                    <div class="col-span-2 mt-4">
                                        <h4 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wide border-t border-slate-100 pt-4">Data Kepegawaian</h4>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                                        <input type="text" wire:model="nip" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        @error('nip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                                        <input type="text" wire:model="jabatan" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        @error('jabatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Hak Akses (Role)</label>
                                        <select wire:model="peran" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                            <option value="admin">Administrator</option>
                                            <option value="dokter">Dokter</option>
                                            <option value="perawat">Perawat / Bidan</option>
                                            <option value="apoteker">Apoteker</option>
                                            <option value="pendaftaran">Petugas Pendaftaran</option>
                                        </select>
                                        @error('peran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Kondisional Field untuk Dokter -->
                                    <div x-data="{ role: @entangle('peran') }" x-show="role === 'dokter'" class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-medium text-emerald-700 mb-1">Nomor SIP (Khusus Dokter)</label>
                                        <input type="text" wire:model="sip" class="block w-full rounded-lg border-emerald-300 bg-emerald-50 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" placeholder="SIP-XXXX-XXXX">
                                    </div>

                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">
                                            Kata Sandi 
                                            @if($modeEdit) <span class="text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span> @endif
                                        </label>
                                        <input type="password" wire:model="sandi_baru" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" placeholder="Minimal 8 karakter">
                                        @if(!$modeEdit)<p class="text-xs text-slate-500 mt-1">Default: 12345678</p>@endif
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