<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Kepegawaian</h1>
            <p class="text-slate-500">Kelola data pegawai, hak akses pengguna, dan informasi personal.</p>
        </div>
        @if(!$tampilkanModal)
        <button wire:click="tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-lg flex items-center gap-2 shadow-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Pegawai Baru
        </button>
        @endif
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('sukses') }}
    </div>
    @endif

    @if($tampilkanModal)
        <!-- FORM VIEW (INLINE) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">
                    {{ $modeEdit ? 'Edit Pegawai' : 'Tambah Pegawai Baru' }}
                </h3>
            </div>
            <form wire:submit="simpan">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Kolom Kiri: Data Pribadi & Kepegawaian -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-emerald-600 uppercase tracking-wider mb-2">Data Kepegawaian</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap (dengan Gelar)</label>
                                <input type="text" wire:model="nama_lengkap" class="w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">NIP</label>
                                    <input type="text" wire:model="nip" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                    @error('nip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">NIK (KTP)</label>
                                    <input type="text" wire:model="nik" class="w-full rounded-lg border-slate-300 sm:text-sm" maxlength="16">
                                    @error('nik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                                    <input type="text" wire:model="jabatan" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                    @error('jabatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                    <select wire:model="status_kepegawaian" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="PNS">PNS</option>
                                        <option value="PPPK">PPPK</option>
                                        <option value="Honorer">Honorer</option>
                                        <option value="Kontrak">Kontrak</option>
                                    </select>
                                    @error('status_kepegawaian') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tempat Lahir</label>
                                    <input type="text" wire:model="tempat_lahir" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" wire:model="tanggal_lahir" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                                    <select wire:model="jenis_kelamin" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Pendidikan Terakhir</label>
                                    <select wire:model="pendidikan_terakhir" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="SMA">SMA/SMK</option>
                                        <option value="D3">D3</option>
                                        <option value="D4">D4</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">SIP (Khusus Dokter)</label>
                                <input type="text" wire:model="sip" class="w-full rounded-lg border-slate-300 sm:text-sm" placeholder="Kosongkan jika bukan dokter">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">STR (Tenaga Medis)</label>
                                <input type="text" wire:model="str" class="w-full rounded-lg border-slate-300 sm:text-sm">
                            </div>
                        </div>

                        <!-- Kolom Kanan: Akun Pengguna -->
                        <div class="space-y-4 bg-slate-50 p-6 rounded-xl border border-slate-200 h-fit">
                            <h4 class="text-sm font-bold text-emerald-600 uppercase tracking-wider mb-2">Akun Sistem</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Email (Username)</label>
                                <input type="email" wire:model="email" class="w-full rounded-lg border-slate-300 sm:text-sm">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Hak Akses (Role)</label>
                                <select wire:model="peran" class="w-full rounded-lg border-slate-300 sm:text-sm bg-white">
                                    <option value="perawat">Perawat</option>
                                    <option value="dokter">Dokter</option>
                                    <option value="admin">Admin (IT/TU)</option>
                                    <option value="pendaftaran">Pendaftaran</option>
                                    <option value="apoteker">Apoteker</option>
                                    <option value="kasir">Kasir</option>
                                    <option value="analis">Analis Lab</option>
                                    <option value="kapus">Kepala Puskesmas</option>
                                </select>
                                @error('peran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                                <input type="password" wire:model="sandi_baru" class="w-full rounded-lg border-slate-300 sm:text-sm" placeholder="{{ $modeEdit ? 'Kosongkan jika tidak diubah' : 'Default: 12345678' }}">
                                @if(!$modeEdit) <p class="text-xs text-slate-500 mt-1">Default: 12345678</p> @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon / WA</label>
                                <input type="text" wire:model="no_telepon" class="w-full rounded-lg border-slate-300 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Domisili</label>
                                <textarea wire:model="alamat" rows="3" class="w-full rounded-lg border-slate-300 sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:text-sm">
                        Simpan Data
                    </button>
                    <button type="button" wire:click="tutupModal" class="inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- LIST VIEW -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Search -->
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="relative max-w-md">
                    <input wire:model.live.debounce.300ms="cari" type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" placeholder="Cari Nama, NIP, atau Jabatan...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pegawai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jabatan & NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Role Akun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($dataPegawai as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-lg">
                                            {{ substr($p->pengguna->nama_lengkap ?? 'X', 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $p->pengguna->nama_lengkap ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $p->status_kepegawaian ?? 'Pegawai' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900 font-medium">{{ $p->jabatan }}</div>
                                <div class="text-xs text-slate-500">NIP: {{ $p->nip ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    {{ $p->pengguna->peran ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                <div>{{ $p->pengguna->email ?? '-' }}</div>
                                <div class="text-xs">{{ $p->pengguna->no_telepon ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button wire:click="edit({{ $p->id }})" class="text-emerald-600 hover:text-emerald-900 mr-3 font-bold">Edit</button>
                                <button wire:click="hapus({{ $p->id }})" class="text-red-600 hover:text-red-900 font-bold" onclick="confirm('Hapus data pegawai ini? Akun terkait juga akan dihapus.') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data pegawai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 sm:px-6">
                {{ $dataPegawai->links() }}
            </div>
        </div>
    @endif
</div>
