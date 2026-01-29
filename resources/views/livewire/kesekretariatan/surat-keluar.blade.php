<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Arsip Surat Keluar</h1>
            <p class="text-slate-500">Manajemen dokumen dinas yang diterbitkan Puskesmas.</p>
        </div>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Arsipkan Surat
        </button>
    </div>

    @if(session('sukses'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('sukses') }}
        </div>
    @endif

    <!-- Tabel Data -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <input type="text" wire:model.live.debounce.300ms="cari" placeholder="Cari nomor, tujuan, atau perihal..." class="w-full md:w-80 rounded-lg border-slate-300 text-sm">
        </div>

        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor & Sifat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tujuan & Perihal</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pembuat</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($dataSurat as $s)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-mono">
                        {{ $s->tanggal_surat->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-900">{{ $s->nomor_surat }}</p>
                        @php
                            $badgeColor = match($s->sifat) {
                                'penting' => 'bg-red-100 text-red-800',
                                'rahasia' => 'bg-purple-100 text-purple-800',
                                default => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium uppercase mt-1 {{ $badgeColor }}">
                            {{ $s->sifat }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-900">Kepada: {{ $s->tujuan_surat }}</p>
                        <p class="text-sm text-slate-600 italic">"{{ $s->perihal }}"</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $s->pembuat->nama_lengkap ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                        @if($s->file_dokumen)
                            <a href="{{ asset('storage/' . $s->file_dokumen) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-bold">Unduh</a>
                        @endif
                        <button wire:click="edit({{ $s->id }})" class="text-emerald-600 hover:text-emerald-900 font-bold">Edit</button>
                        <button wire:click="hapus({{ $s->id }})" class="text-red-600 hover:text-red-900 font-bold" onclick="confirm('Hapus arsip ini?') || event.stopImmediatePropagation()">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada surat keluar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $dataSurat->links() }}
        </div>
    </div>

    <!-- MODAL FORM -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">{{ $modeEdit ? 'Edit Arsip Surat' : 'Arsipkan Surat Keluar' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Nomor Surat</label>
                                <input type="text" wire:model="nomor_surat" class="w-full rounded-lg border-slate-300">
                                @error('nomor_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Tujuan Surat</label>
                                <input type="text" wire:model="tujuan_surat" class="w-full rounded-lg border-slate-300" placeholder="Instansi/Perorangan">
                                @error('tujuan_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Perihal</label>
                                <textarea wire:model="perihal" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                                @error('perihal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal Surat</label>
                                    <input type="date" wire:model="tanggal_surat" class="w-full rounded-lg border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Sifat</label>
                                    <select wire:model="sifat" class="w-full rounded-lg border-slate-300">
                                        <option value="biasa">Biasa</option>
                                        <option value="penting">Penting</option>
                                        <option value="rahasia">Rahasia</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Pembuat Konsep (Opsional)</label>
                                <select wire:model="id_pembuat" class="w-full rounded-lg border-slate-300">
                                    <option value="">-- Saya Sendiri --</option>
                                    @foreach($pegawaiList as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">File Dokumen (PDF/Gambar)</label>
                                @if($file_dokumen_lama && !$file_dokumen)
                                    <p class="text-xs text-blue-600 mb-2">File saat ini: Tersedia</p>
                                @endif
                                <input type="file" wire:model="file_dokumen" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('file_dokumen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" wire:click="tutupModal" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:text-slate-500 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
