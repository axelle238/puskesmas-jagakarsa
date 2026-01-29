<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Indikator Mutu & Kinerja</h1>
            <p class="text-slate-500">Pemantauan Indikator Nasional Mutu (INM) dan Indikator Mutu Prioritas.</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="bulan" class="rounded-lg border-slate-300 text-sm font-bold">
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                @endforeach
            </select>
            <select wire:model.live="tahun" class="rounded-lg border-slate-300 text-sm font-bold">
                @for($i = date('Y'); $i >= 2024; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <button wire:click="tambahMaster" class="bg-slate-800 text-white px-4 py-2 rounded-lg font-bold text-sm">
                + Indikator Baru
            </button>
        </div>
    </div>

    @if(session('sukses'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            {{ session('sukses') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($dataIndikator as $i)
            @php
                $hasil = $i->hasil->first();
                $capaian = $hasil ? $hasil->capaian : 0;
                $target = $i->target_capaian;
                $status = $capaian >= $target ? 'Tercapai' : 'Tidak Tercapai';
                $color = $capaian >= $target ? 'emerald' : 'red';
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded uppercase">{{ $i->tipe }}</span>
                        <span class="text-xs text-slate-400 font-bold">{{ $i->unit_terkait }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-1 leading-tight">{{ $i->judul_indikator }}</h3>
                    <p class="text-sm text-slate-500 mb-4">Target: ≥ {{ $target }}%</p>
                </div>

                <div class="mt-4">
                    <div class="flex items-end justify-between mb-1">
                        <span class="text-sm font-bold text-slate-600">Capaian Bulan Ini</span>
                        <span class="text-2xl font-black text-{{ $color }}-600">{{ $capaian }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5">
                        <div class="bg-{{ $color }}-500 h-2.5 rounded-full" style="width: {{ min($capaian, 100) }}%"></div>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <button wire:click="inputCapaian({{ $i->id }})" class="text-blue-600 text-sm font-bold hover:underline">
                            {{ $hasil ? 'Edit Data' : 'Input Data' }}
                        </button>
                        @if($hasil && $hasil->analisis)
                             <span class="text-xs text-slate-400 cursor-help" title="Analisis: {{ $hasil->analisis }}">Lihat Analisis</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- MODAL MASTER INDIKATOR -->
    @if($modalMaster)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900 bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg mb-4">Tambah Indikator Mutu</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1">Judul Indikator</label>
                    <input type="text" wire:model="judul_indikator" class="w-full rounded-lg border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Tipe</label>
                        <select wire:model="tipe" class="w-full rounded-lg border-slate-300">
                            <option value="nasional">Nasional (INM)</option>
                            <option value="prioritas">Prioritas PKM</option>
                            <option value="unit">Mutu Unit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Target (%)</label>
                        <input type="number" wire:model="target_capaian" class="w-full rounded-lg border-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Unit Terkait</label>
                    <input type="text" wire:model="unit_terkait" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Poli Umum">
                </div>
                <button wire:click="simpanMaster" class="w-full bg-slate-900 text-white py-2 rounded-lg font-bold mt-2">Simpan Indikator</button>
                <button wire:click="$set('modalMaster', false)" class="w-full text-slate-500 py-2 text-sm">Batal</button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL INPUT HASIL -->
    @if($modalInput)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900 bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-lg p-6">
            <h3 class="font-bold text-lg mb-1">Input Capaian Mutu</h3>
            <p class="text-sm text-slate-500 mb-4">Periode: {{ $bulan }}-{{ $tahun }}</p>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Numerator (Pembilang)</label>
                        <input type="number" wire:model="pembilang" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Denominator (Penyebut)</label>
                        <input type="number" wire:model="penyebut" class="w-full rounded-lg border-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Analisis Masalah</label>
                    <textarea wire:model="analisis" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Rencana Tindak Lanjut</label>
                    <textarea wire:model="tindak_lanjut" rows="2" class="w-full rounded-lg border-slate-300"></textarea>
                </div>
                
                <button wire:click="simpanCapaian" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold mt-2">Simpan Data</button>
                <button wire:click="$set('modalInput', false)" class="w-full text-slate-500 py-2 text-sm">Batal</button>
            </div>
        </div>
    </div>
    @endif
</div>
