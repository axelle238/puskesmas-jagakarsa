<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pasien</h1>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow transition">
            <span>+</span> Tambah Pasien
        </button>
    </div>

    <!-- Alert -->
    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('pesan') }}</span>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="relative">
            <input wire:model.live.debounce.300ms="cari" type="text" placeholder="Cari berdasarkan Nama, NIK, atau No. RM..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <div class="absolute left-3 top-2.5 text-gray-400">🔍</div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">No. RM</th>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">NIK</th>
                    <th class="px-6 py-4">Info Kontak</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pasiens as $pasien)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-mono font-bold text-blue-600">{{ $pasien->no_rekam_medis }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $pasien->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Thn</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $pasien->nik }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        @if($pasien->no_bpjs)
                        <span class="block text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded w-fit mb-1">BPJS: {{ $pasien->no_bpjs }}</span>
                        @endif
                        <span class="text-xs text-gray-500">{{ Str::limit($pasien->alamat_lengkap, 30) }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button wire:click="edit({{ $pasien->id }})" class="text-blue-600 hover:bg-blue-50 p-2 rounded transition" title="Edit">✏️</button>
                            <button wire:click="hapus({{ $pasien->id }})" wire:confirm="Yakin ingin menghapus data pasien ini?" class="text-red-600 hover:bg-red-50 p-2 rounded transition" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $pasiens->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <h3 class="font-bold text-lg text-gray-800">{{ $modeEdit ? 'Edit Data Pasien' : 'Tambah Pasien Baru' }}</h3>
                <button wire:click="$set('tampilkanModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <form wire:submit="simpan" class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK (16 Digit)</label>
                        <input type="text" wire:model="nik" maxlength="16" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                        @error('nik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="nama_lengkap" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                        @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" wire:model="tempat_lahir" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                        @error('tempat_lahir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" wire:model="tanggal_lahir" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                        @error('tanggal_lahir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select wire:model="jenis_kelamin" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label>
                        <select wire:model="golongan_darah" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            <option value="">-</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor BPJS (Opsional)</label>
                        <input type="text" wire:model="no_bpjs" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Kontak Darurat</label>
                        <input type="text" wire:model="no_telepon_darurat" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea wire:model="alamat_lengkap" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
                    @error('alamat_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" wire:click="$set('tampilkanModal', false)" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-bold shadow transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>