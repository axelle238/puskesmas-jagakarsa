<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pegawai</h1>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow transition">
            <span>+</span> Tambah Pegawai
        </button>
    </div>

    <!-- Alert -->
    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('pesan') }}</span>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Nama & NIP</th>
                    <th class="px-6 py-4">Jabatan</th>
                    <th class="px-6 py-4">Peran & Login</th>
                    <th class="px-6 py-4">Status Medis</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pegawais as $pegawai)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $pegawai->pengguna->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500 font-mono">{{ $pegawai->nip ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800">{{ $pegawai->jabatan }}</div>
                        @if($pegawai->spesialisasi)
                            <div class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded w-fit">{{ $pegawai->spesialisasi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold uppercase">{{ $pegawai->pengguna->peran }}</span>
                        <div class="text-xs text-gray-400 mt-1">{{ $pegawai->pengguna->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-xs">
                        @if($pegawai->sip)
                            <div class="text-green-600 font-semibold">SIP: {{ $pegawai->sip }}</div>
                        @endif
                        @if($pegawai->str)
                            <div class="text-gray-500">STR: {{ $pegawai->str }}</div>
                        @endif
                        @if(!$pegawai->sip && !$pegawai->str)
                            <span class="text-gray-400 italic">Non-Medis</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button wire:click="edit({{ $pegawai->id }})" class="text-blue-600 hover:bg-blue-50 p-2 rounded transition">✏️</button>
                            <button wire:click="hapus({{ $pegawai->id }})" wire:confirm="Yakin ingin menghapus pegawai ini?" class="text-red-600 hover:bg-red-50 p-2 rounded transition">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data pegawai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $pegawais->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <h3 class="font-bold text-lg text-gray-800">{{ $modeEdit ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru' }}</h3>
                <button wire:click="$set('tampilkanModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <form wire:submit="simpan" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Kolom Kiri: Akun -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-gray-800 border-b pb-2">Informasi Akun</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="nama_lengkap" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email (Login)</label>
                            <input type="email" wire:model="email" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi {{ $modeEdit ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                            <input type="password" wire:model="sandi" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            @error('sandi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peran Sistem</label>
                            <select wire:model="peran" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                                <option value="">Pilih Peran</option>
                                <option value="dokter">Dokter</option>
                                <option value="perawat">Perawat</option>
                                <option value="apoteker">Apoteker</option>
                                <option value="pendaftaran">Petugas Pendaftaran</option>
                                <option value="admin">Administrator</option>
                            </select>
                            @error('peran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan: Data Pegawai -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-gray-800 border-b pb-2">Data Kepegawaian</h4>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" wire:model="nip" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk</label>
                                <input type="date" wire:model="tanggal_masuk" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" wire:model="jabatan" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Contoh: Dokter Umum, Kepala Tata Usaha">
                            @error('jabatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">STR (Nakes)</label>
                                <input type="text" wire:model="str" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SIP (Dokter)</label>
                                <input type="text" wire:model="sip" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi (Jika Ada)</label>
                            <input type="text" wire:model="spesialisasi" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Contoh: Kandungan, Gigi">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                    <button type="button" wire:click="$set('tampilkanModal', false)" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-bold shadow transition">Simpan Pegawai</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
