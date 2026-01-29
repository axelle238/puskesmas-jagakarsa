<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Praktik Dokter</h1>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow transition">
            <span>+</span> Tambah Jadwal
        </button>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Hari</th>
                    <th class="px-6 py-4">Jam Praktik</th>
                    <th class="px-6 py-4">Dokter</th>
                    <th class="px-6 py-4">Poli</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($jadwals as $jadwal)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $jadwal->hari }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $jadwal->dokter->pengguna->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">{{ $jadwal->dokter->spesialisasi ?? 'Umum' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold">{{ $jadwal->poli->nama_poli }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($jadwal->aktif)
                            <span class="text-green-600 text-xs font-bold bg-green-100 px-2 py-1 rounded">Aktif</span>
                        @else
                            <span class="text-gray-400 text-xs font-bold bg-gray-100 px-2 py-1 rounded">Libur</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="edit({{ $jadwal->id }})" class="text-blue-600 hover:underline mr-2">Edit</button>
                        <button wire:click="hapus({{ $jadwal->id }})" wire:confirm="Hapus jadwal ini?" class="text-red-600 hover:underline">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">{{ $modeEdit ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}</h3>
            
            <form wire:submit="simpan" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                        <select wire:model="hari" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poli</label>
                        <select wire:model="id_poli" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Pilih Poli</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dokter</label>
                    <select wire:model="id_dokter" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Pilih Dokter</option>
                        @foreach($dokters as $dokter)
                            <option value="{{ $dokter->id }}">{{ $dokter->pengguna->nama_lengkap }} ({{ $dokter->sip }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" wire:model="jam_mulai" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" wire:model="jam_selesai" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuota Pasien</label>
                        <input type="number" wire:model="kuota_pasien" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div class="flex items-center pt-6">
                        <input type="checkbox" wire:model="aktif" class="w-4 h-4 text-blue-600 rounded">
                        <label class="ml-2 text-sm text-gray-700">Jadwal Aktif</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="$set('tampilkanModal', false)" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
