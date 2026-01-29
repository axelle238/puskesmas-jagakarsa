<div class="max-w-7xl mx-auto pb-12">
    <!-- Header Pasien Sticky -->
    <div class="sticky top-0 z-30 bg-white shadow-md rounded-b-xl border-t border-gray-100 -mx-6 px-6 py-4 mb-8">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                    {{ substr($pasien->nama_lengkap, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $pasien->nama_lengkap }}</h2>
                    <div class="flex gap-4 text-sm text-gray-500">
                        <span class="font-mono bg-gray-100 px-2 rounded">{{ $pasien->no_rekam_medis }}</span>
                        <span>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span>{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button wire:click="$set('tabAktif', 'periksa')" class="px-4 py-2 rounded-lg font-bold transition {{ $tabAktif == 'periksa' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    🩺 Form Periksa
                </button>
                <button wire:click="$set('tabAktif', 'riwayat')" class="px-4 py-2 rounded-lg font-bold transition {{ $tabAktif == 'riwayat' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    📂 Riwayat Medis
                </button>
            </div>
        </div>
    </div>

    <!-- Tampilan Form Pemeriksaan -->
    <div class="{{ $tabAktif == 'periksa' ? 'block' : 'hidden' }}">
        <form wire:submit="simpanPemeriksaan">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Kolom Kiri: SOAP -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Subjektif -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded flex items-center justify-center text-sm">S</span>
                            Anamnesa (Subjektif)
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan Utama</label>
                                <textarea wire:model="keluhan_utama" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Apa yang dirasakan pasien?"></textarea>
                                @error('keluhan_utama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Riwayat Penyakit</label>
                                <textarea wire:model="riwayat_penyakit" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Riwayat penyakit sekarang/dahulu..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Objektif -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 w-8 h-8 rounded flex items-center justify-center text-sm">O</span>
                            Pemeriksaan Fisik (Objektif)
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Tekanan Darah</label>
                                <div class="flex items-center gap-1">
                                    <input type="number" wire:model="tanda_vital.sistole" class="w-full px-2 py-2 border rounded text-center" placeholder="120">
                                    <span>/</span>
                                    <input type="number" wire:model="tanda_vital.diastole" class="w-full px-2 py-2 border rounded text-center" placeholder="80">
                                </div>
                                <span class="text-xs text-gray-400">mmHg</span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Suhu Tubuh</label>
                                <input type="number" step="0.1" wire:model="tanda_vital.suhu" class="w-full px-2 py-2 border rounded text-center" placeholder="36.5">
                                <span class="text-xs text-gray-400">°Celsius</span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Nadi (HR)</label>
                                <input type="number" wire:model="tanda_vital.nadi" class="w-full px-2 py-2 border rounded text-center" placeholder="80">
                                <span class="text-xs text-gray-400">bpm</span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Pernapasan (RR)</label>
                                <input type="number" wire:model="tanda_vital.rr" class="w-full px-2 py-2 border rounded text-center" placeholder="20">
                                <span class="text-xs text-gray-400">x/menit</span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Berat Badan</label>
                                <input type="number" step="0.1" wire:model="tanda_vital.bb" class="w-full px-2 py-2 border rounded text-center" placeholder="60">
                                <span class="text-xs text-gray-400">kg</span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Tinggi Badan</label>
                                <input type="number" wire:model="tanda_vital.tb" class="w-full px-2 py-2 border rounded text-center" placeholder="170">
                                <span class="text-xs text-gray-400">cm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Asesmen -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                            <span class="bg-yellow-100 text-yellow-600 w-8 h-8 rounded flex items-center justify-center text-sm">A</span>
                            Diagnosis (Asesmen)
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode ICD-10</label>
                                    <input type="text" wire:model="diagnosis_kode" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Contoh: A09">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis Klinis</label>
                                    <input type="text" wire:model="diagnosis_text" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Contoh: Gastroenteritis Akut">
                                    @error('diagnosis_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Plan & Resep -->
                <div class="space-y-6">
                    
                    <!-- Plan & Terapi -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                            <span class="bg-purple-100 text-purple-600 w-8 h-8 rounded flex items-center justify-center text-sm">P</span>
                            Perencanaan (Plan)
                        </h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instruksi Medis / Edukasi</label>
                            <textarea wire:model="plan_terapi" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Istirahat cukup, banyak minum air..."></textarea>
                        </div>

                        <!-- Tindakan -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tindakan Medis</label>
                            @foreach($tindakan_input as $index => $tindakan)
                            <div class="flex gap-2 mb-2">
                                <select wire:model="tindakan_input.{{ $index }}.id_tindakan" class="flex-1 px-2 py-2 border rounded text-sm">
                                    <option value="">Pilih Tindakan</option>
                                    @foreach($list_tindakan as $lTindakan)
                                        <option value="{{ $lTindakan->id }}">{{ $lTindakan->nama_tindakan }}</option>
                                    @endforeach
                                </select>
                                <button type="button" wire:click="hapusTindakan({{ $index }})" class="text-red-500 hover:text-red-700">&times;</button>
                            </div>
                            @endforeach
                            <button type="button" wire:click="tambahTindakan" class="text-xs text-blue-600 font-bold hover:underline">+ Tambah Tindakan</button>
                        </div>
                    </div>

                    <!-- Resep Obat -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-red-500">
                        <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                            <span>💊</span> Resep Obat
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach($resep_input as $index => $resep)
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 relative">
                                <button type="button" wire:click="hapusResep({{ $index }})" class="absolute top-2 right-2 text-red-400 hover:text-red-600 font-bold text-lg leading-none">&times;</button>
                                
                                <div class="mb-2">
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Nama Obat</label>
                                    <select wire:model="resep_input.{{ $index }}.id_obat" class="w-full px-2 py-1.5 border rounded text-sm bg-white">
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach($list_obat as $obat)
                                            <option value="{{ $obat->id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok_saat_ini }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="flex gap-2">
                                    <div class="w-1/3">
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Jumlah</label>
                                        <input type="number" wire:model="resep_input.{{ $index }}.jumlah" class="w-full px-2 py-1.5 border rounded text-center text-sm" placeholder="10">
                                    </div>
                                    <div class="w-2/3">
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Aturan Pakai</label>
                                        <input type="text" wire:model="resep_input.{{ $index }}.aturan_pakai" class="w-full px-2 py-1.5 border rounded text-sm" placeholder="3x1 Tablet">
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <button type="button" wire:click="tambahResep" class="w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 font-bold hover:border-blue-500 hover:text-blue-500 transition">
                                + Tambah Obat
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1 text-lg">
                        ✅ Selesai Pemeriksaan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tampilan Riwayat Medis -->
    <div class="{{ $tabAktif == 'riwayat' ? 'block' : 'hidden' }} space-y-6">
        @forelse($riwayatMedis as $riwayat)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <span class="font-bold text-gray-800">{{ $riwayat->created_at->format('d F Y - H:i') }}</span>
                <span class="text-sm text-gray-500">{{ $riwayat->poli->nama_poli }} • dr. {{ $riwayat->dokter->pengguna->nama_lengkap }}</span>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold text-sm text-gray-500 uppercase mb-2">Diagnosa</h4>
                    <p class="font-mono text-blue-600 font-bold">{{ $riwayat->diagnosis_kode ?? '-' }}</p>
                    <p class="text-gray-800">{{ $riwayat->asesmen }}</p>
                    <p class="text-sm text-gray-600 mt-2 italic">"{{ $riwayat->keluhan_utama }}"</p>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-gray-500 uppercase mb-2">Resep Obat</h4>
                    <ul class="list-disc list-inside text-sm text-gray-700">
                        @foreach($riwayat->resepDetail as $obat)
                            <li>{{ $obat->nama_obat }} ({{ $obat->pivot->jumlah }} {{ $obat->satuan }}) - {{ $obat->pivot->aturan_pakai }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-dashed border-gray-300">
            Belum ada riwayat medis sebelumnya.
        </div>
        @endforelse
    </div>
</div>