<div class="max-w-7xl mx-auto">
    <!-- Header Pasien -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-2xl font-bold">
                {{ substr($pasien->nama_lengkap, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ $pasien->nama_lengkap }}</h1>
                <div class="flex flex-wrap gap-3 text-sm text-slate-500 mt-1">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        {{ $pasien->no_rekam_medis }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun
                    </span>
                </div>
            </div>
        </div>
        <div>
            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">
                {{ $antrian->poli->nama_poli }}
            </span>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Sidebar: Tabs & History -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Navigation Tabs -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <nav class="flex flex-col">
                    <button wire:click="$set('activeTab', 'soap')" class="px-6 py-4 text-left font-medium text-sm flex items-center justify-between transition-colors {{ $activeTab === 'soap' ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>1. Pemeriksaan (SOAP)</span>
                        @if($activeTab === 'soap')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </button>
                    <button wire:click="$set('activeTab', 'resep')" class="px-6 py-4 text-left font-medium text-sm flex items-center justify-between transition-colors border-t border-slate-100 {{ $activeTab === 'resep' ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>2. E-Resep & Obat</span>
                        <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-xs">{{ count($resepList) }}</span>
                    </button>
                    <button wire:click="$set('activeTab', 'riwayat')" class="px-6 py-4 text-left font-medium text-sm flex items-center justify-between transition-colors border-t border-slate-100 {{ $activeTab === 'riwayat' ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>3. Riwayat Medis</span>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                </nav>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                <button wire:click="simpanPemeriksaan" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan & Selesai
                </button>
                <a href="{{ route('medis.antrian') }}" class="block text-center w-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold py-3 rounded-lg">
                    Batal / Kembali
                </a>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="lg:col-span-2">
            
            <!-- TAB 1: SOAP -->
            @if($activeTab === 'soap')
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-2">Catatan Pemeriksaan (SOAP)</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Subjektif (Keluhan Utama)</label>
                        <textarea wire:model="keluhan_utama" rows="2" class="w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Apa yang dikeluhkan pasien?"></textarea>
                        @error('keluhan_utama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Subjektif Tambahan (Anamnesis)</label>
                        <textarea wire:model="subjektif" rows="3" class="w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Detail keluhan, riwayat penyakit sekarang..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Objektif (Pemeriksaan Fisik)</label>
                        <textarea wire:model="objektif" rows="3" class="w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Tanda vital, hasil pemeriksaan fisik..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Asesmen (Diagnosa Kerja)</label>
                            <input type="text" wire:model="asesmen" class="w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: ISPA, Febris">
                            @error('asesmen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Kode Diagnosa (ICD-10)</label>
                            <input type="text" wire:model="diagnosis_kode" class="w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: J06.9">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Planning (Rencana/Tindakan)</label>
                        <textarea wire:model="plan" rows="2" class="w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Edukasi, rencana tindak lanjut..."></textarea>
                    </div>
                </div>
            </div>
            @endif

            <!-- TAB 2: RESEP -->
            @if($activeTab === 'resep')
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-2">Input Resep Obat</h2>
                
                <!-- Form Tambah Obat -->
                <div class="bg-slate-50 p-4 rounded-lg mb-6 border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-slate-500 mb-1">Nama Obat</label>
                            <select wire:model="inputResep.id_obat" class="w-full rounded-md border-slate-300 text-sm">
                                <option value="">-- Pilih Obat --</option>
                                @foreach($obatList as $o)
                                    <option value="{{ $o->id }}">{{ $o->nama_obat }} (Stok: {{ $o->stok_saat_ini }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 mb-1">Jumlah</label>
                            <input type="number" wire:model="inputResep.jumlah" class="w-full rounded-md border-slate-300 text-sm" min="1">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-500 mb-1">Aturan Pakai</label>
                            <input type="text" wire:model="inputResep.dosis" class="w-full rounded-md border-slate-300 text-sm" placeholder="3x1">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 mb-1">Catatan</label>
                            <input type="text" wire:model="inputResep.catatan" class="w-full rounded-md border-slate-300 text-sm" placeholder="Gerus/Lainnya">
                        </div>
                        <div class="md:col-span-1">
                            <button wire:click="tambahObat" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-md p-2 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- List Resep -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Nama Obat</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Jml</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Aturan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Catatan</th>
                                <th class="px-4 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($resepList as $index => $resep)
                            <tr>
                                <td class="px-4 py-2 text-sm text-slate-900 font-medium">{{ $resep['nama_obat'] }}</td>
                                <td class="px-4 py-2 text-sm text-slate-700">{{ $resep['jumlah'] }}</td>
                                <td class="px-4 py-2 text-sm text-slate-700">{{ $resep['dosis'] }}</td>
                                <td class="px-4 py-2 text-sm text-slate-500">{{ $resep['catatan'] ?? '-' }}</td>
                                <td class="px-4 py-2 text-right">
                                    <button wire:click="hapusObat({{ $index }})" class="text-red-500 hover:text-red-700 text-xs font-bold">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada obat ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- TAB 3: RIWAYAT -->
            @if($activeTab === 'riwayat')
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-2">Riwayat Kunjungan</h2>
                
                <div class="space-y-6">
                    @forelse($riwayatKunjungan as $rw)
                    <div class="border-l-4 border-slate-200 pl-4 py-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $rw->created_at->isoFormat('D MMMM Y') }}</p>
                                <p class="text-xs text-slate-500 mb-2">Poli Umum • dr. Budi Santoso</p>
                            </div>
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">Selesai</span>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3 text-sm text-slate-700 space-y-1">
                            <p><span class="font-bold text-slate-500">S:</span> {{ $rw->keluhan_utama }}</p>
                            <p><span class="font-bold text-slate-500">O:</span> {{ $rw->objektif ?? '-' }}</p>
                            <p><span class="font-bold text-slate-500">A:</span> {{ $rw->asesmen }}</p>
                            <p><span class="font-bold text-slate-500">P:</span> {{ $rw->plan ?? '-' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-slate-400">
                        <p>Belum ada riwayat kunjungan sebelumnya.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
