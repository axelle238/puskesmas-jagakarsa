<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Arsip Surat Masuk</h1>
            <p class="text-slate-500">Pencatatan dan disposisi surat dinas eksternal.</p>
        </div>
        <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Catat Surat Masuk
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
            <input type="text" wire:model.live.debounce.300ms="cari" placeholder="Cari nomor surat atau perihal..." class="w-full md:w-80 rounded-lg border-slate-300 text-sm">
        </div>

        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Terima</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Info Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Asal Pengirim</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($dataSurat as $s)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $s->tanggal_diterima->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-900">{{ $s->nomor_surat }}</p>
                        <p class="text-sm text-slate-600">{{ $s->perihal }}</p>
                        @if($s->sifat != 'biasa')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 uppercase mt-1">
                                {{ $s->sifat }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $s->asal_surat }}
                        <div class="text-xs text-slate-400 mt-1">Tgl Surat: {{ $s->tanggal_surat->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($s->status == 'menunggu_disposisi')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Disposisi</span>
                        @elseif($s->status == 'didisposisi')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Didisposisi</span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">Selesai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                        @if($s->file_dokumen)
                            <a href="{{ asset('storage/' . $s->file_dokumen) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-bold">Unduh</a>
                        @endif
                        <button wire:click="bukaDisposisi({{ $s->id }})" class="text-emerald-600 hover:text-emerald-900 font-bold">Disposisi</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada surat masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $dataSurat->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH SURAT -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Catat Surat Masuk</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Nomor Surat</label>
                                <input type="text" wire:model="nomor_surat" class="w-full rounded-lg border-slate-300">
                                @error('nomor_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Asal Pengirim</label>
                                <input type="text" wire:model="asal_surat" class="w-full rounded-lg border-slate-300" placeholder="Instansi/Perorangan">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Perihal</label>
                                <textarea wire:model="perihal" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Tgl Surat</label>
                                    <input type="date" wire:model="tanggal_surat" class="w-full rounded-lg border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Tgl Diterima</label>
                                    <input type="date" wire:model="tanggal_diterima" class="w-full rounded-lg border-slate-300">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Sifat Surat</label>
                                <select wire:model="sifat" class="w-full rounded-lg border-slate-300">
                                    <option value="biasa">Biasa</option>
                                    <option value="penting">Penting</option>
                                    <option value="rahasia">Rahasia</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">File Scan (PDF/Gambar)</label>
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

    <!-- MODAL DISPOSISI -->
    @if($tampilkanModalDisposisi && $suratTerpilih)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="mb-6 border-b border-slate-100 pb-4">
                        <h3 class="text-lg font-bold text-slate-900">Lembar Disposisi</h3>
                        <p class="text-sm text-slate-500">No: {{ $suratTerpilih->nomor_surat }} | Dari: {{ $suratTerpilih->asal_surat }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Form Disposisi -->
                        <div>
                            <h4 class="font-bold text-sm uppercase text-slate-400 mb-3">Buat Disposisi Baru</h4>
                            @if(session('sukses_disposisi'))
                                <div class="mb-4 p-2 bg-emerald-50 text-emerald-700 text-xs rounded">{{ session('sukses_disposisi') }}</div>
                            @endif
                            <form wire:submit="simpanDisposisi" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold mb-1">Teruskan Ke</label>
                                    <select wire:model="ke_pegawai_id" class="w-full rounded-lg border-slate-300 text-sm">
                                        <option value="">Pilih Pegawai</option>
                                        @foreach($pegawaiList as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_lengkap }} ({{ $p->jabatan }})</option>
                                        @endforeach
                                    </select>
                                    @error('ke_pegawai_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-1">Instruksi</label>
                                    <select wire:model="instruksi" class="w-full rounded-lg border-slate-300 text-sm">
                                        <option value="">Pilih Instruksi</option>
                                        <option value="Tindak Lanjuti">Tindak Lanjuti</option>
                                        <option value="Untuk Diketahui">Untuk Diketahui</option>
                                        <option value="Siapkan Jawaban">Siapkan Jawaban</option>
                                        <option value="Arsipkan">Arsipkan</option>
                                        <option value="Hadir Mewakili">Hadir Mewakili</option>
                                    </select>
                                    @error('instruksi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-1">Catatan</label>
                                    <textarea wire:model="catatan_tambahan" rows="2" class="w-full rounded-lg border-slate-300 text-sm"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-1">Batas Waktu</label>
                                    <input type="date" wire:model="batas_waktu" class="w-full rounded-lg border-slate-300 text-sm">
                                </div>
                                <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded-lg font-bold text-sm hover:bg-slate-800">Kirim Disposisi</button>
                            </form>
                        </div>

                        <!-- Riwayat Disposisi -->
                        <div class="bg-slate-50 p-4 rounded-lg overflow-y-auto max-h-96">
                            <h4 class="font-bold text-sm uppercase text-slate-400 mb-3">Riwayat Disposisi</h4>
                            <div class="space-y-3">
                                @forelse($suratTerpilih->disposisi as $d)
                                <div class="bg-white p-3 rounded border border-slate-200 shadow-sm relative">
                                    <div class="absolute top-2 right-2 text-xs text-slate-400">{{ $d->created_at->format('d/m H:i') }}</div>
                                    <p class="text-xs font-bold text-slate-500 uppercase">Kepada</p>
                                    <p class="font-bold text-slate-900 text-sm">{{ $d->kePegawai->nama_lengkap ?? '-' }}</p>
                                    
                                    <p class="text-xs font-bold text-slate-500 uppercase mt-2">Instruksi</p>
                                    <p class="text-blue-600 font-bold text-sm">{{ $d->instruksi }}</p>
                                    
                                    @if($d->catatan_tambahan)
                                        <p class="text-xs text-slate-600 italic mt-1">"{{ $d->catatan_tambahan }}"</p>
                                    @endif
                                </div>
                                @empty
                                <div class="text-center text-slate-400 text-xs py-4">Belum ada disposisi.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="tutupModal" class="w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:text-slate-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>