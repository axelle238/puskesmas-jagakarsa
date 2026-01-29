<div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Agenda Promosi Kesehatan</h1>
            <p class="text-slate-500">Jadwal penyuluhan dan dokumentasi kegiatan lapangan.</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="bulan" class="rounded-lg border-slate-300 text-sm font-bold">
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                @endforeach
            </select>
            <select wire:model.live="tahun" class="rounded-lg border-slate-300 text-sm font-bold">
                @for($i = date('Y')+1; $i >= 2024; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <button wire:click="tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Jadwal Baru
            </button>
        </div>
    </div>

    @if(session('sukses'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            {{ session('sukses') }}
        </div>
    @endif

    <!-- Timeline / List Jadwal -->
    <div class="space-y-4">
        @forelse($jadwalKegiatan as $j)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col md:flex-row gap-6 relative overflow-hidden group">
            <!-- Tanggal (Kiri) -->
            <div class="flex-shrink-0 text-center w-full md:w-20 bg-slate-50 rounded-lg p-2 md:py-4 flex flex-col justify-center border border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase">{{ $j->tanggal_kegiatan->format('M') }}</span>
                <span class="text-3xl font-black text-slate-800">{{ $j->tanggal_kegiatan->format('d') }}</span>
                <span class="text-xs font-medium text-slate-400">{{ $j->tanggal_kegiatan->format('D') }}</span>
            </div>

            <!-- Konten (Tengah) -->
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition">{{ $j->topik_kegiatan }}</h3>
                        <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-600">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $j->jam_mulai->format('H:i') }} - {{ $j->jam_selesai ? $j->jam_selesai->format('H:i') : 'Selesai' }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $j->lokasi }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                PJ: {{ $j->petugas->nama_lengkap ?? '-' }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="text-right">
                        @if($j->status == 'rencana')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Rencana
                            </span>
                        @elseif($j->status == 'terlaksana')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                Terlaksana
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Batal
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Footer (Sasaran & Actions) -->
                <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Sasaran: {{ $j->sasaran_peserta }}</p>
                    
                    <div class="flex gap-3">
                        @if($j->status != 'batal')
                            <button wire:click="lapor({{ $j->id }})" class="text-sm font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                {{ $j->laporan ? 'Edit Laporan' : 'Input Laporan' }}
                            </button>
                        @endif
                        <button wire:click="edit({{ $j->id }})" class="text-sm font-bold text-blue-600 hover:text-blue-800">Edit</button>
                        <button wire:click="hapus({{ $j->id }})" class="text-sm font-bold text-red-600 hover:text-red-800" onclick="confirm('Hapus jadwal?') || event.stopImmediatePropagation()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-slate-400 bg-white rounded-xl border border-dashed border-slate-300">
            Belum ada jadwal kegiatan untuk bulan ini.
        </div>
        @endforelse
    </div>

    <!-- MODAL FORM JADWAL -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">{{ $modeEdit ? 'Edit Jadwal' : 'Tambah Jadwal Kegiatan' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Topik Kegiatan</label>
                                <input type="text" wire:model="topik_kegiatan" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Penyuluhan Gizi Balita">
                                @error('topik_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Lokasi</label>
                                <input type="text" wire:model="lokasi" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Posyandu Mawar 1">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal</label>
                                    <input type="date" wire:model="tanggal_kegiatan" class="w-full rounded-lg border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Jam Mulai</label>
                                    <input type="time" wire:model="jam_mulai" class="w-full rounded-lg border-slate-300">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Sasaran Peserta</label>
                                <input type="text" wire:model="sasaran_peserta" class="w-full rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Petugas PJ</label>
                                <select wire:model="id_petugas" class="w-full rounded-lg border-slate-300">
                                    <option value="">Pilih Pegawai</option>
                                    @foreach($pegawaiList as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Status</label>
                                <select wire:model="status" class="w-full rounded-lg border-slate-300">
                                    <option value="rencana">Rencana</option>
                                    <option value="terlaksana">Terlaksana</option>
                                    <option value="batal">Batal</option>
                                </select>
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

    <!-- MODAL LAPORAN -->
    @if($tampilkanModalLaporan && $jadwalTerpilih)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="simpanLaporan">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Laporan Kegiatan Promkes</h3>
                        <p class="text-sm text-slate-500 mb-4">{{ $jadwalTerpilih->topik_kegiatan }} ({{ $jadwalTerpilih->tanggal_kegiatan->format('d M Y') }})</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Jumlah Peserta Hadir</label>
                                <input type="number" wire:model="jumlah_peserta_hadir" class="w-full rounded-lg border-slate-300">
                                @error('jumlah_peserta_hadir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Materi Disampaikan / Ringkasan</label>
                                <textarea wire:model="materi_disampaikan" rows="3" class="w-full rounded-lg border-slate-300"></textarea>
                                @error('materi_disampaikan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Hasil Evaluasi / Tanya Jawab</label>
                                <textarea wire:model="hasil_evaluasi" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Foto Dokumentasi</label>
                                @if($foto_dokumentasi)
                                    <img src="{{ $foto_dokumentasi->temporaryUrl() }}" class="w-full h-32 object-cover rounded mb-2">
                                @elseif($jadwalTerpilih->laporan && $jadwalTerpilih->laporan->foto_dokumentasi)
                                    <img src="{{ asset('storage/' . $jadwalTerpilih->laporan->foto_dokumentasi) }}" class="w-full h-32 object-cover rounded mb-2">
                                @endif
                                <input type="file" wire:model="foto_dokumentasi" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Laporan
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
