<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Antrian Resep Masuk</h1>
            <p class="text-slate-500">Daftar resep dari poli yang perlu disiapkan.</p>
        </div>
        <div class="bg-emerald-100 text-emerald-800 px-4 py-2 rounded-lg text-sm font-bold">
            {{ $resepMasuk->count() }} Resep Menunggu
        </div>
    </div>

    @if(session('sukses'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
        {{ session('sukses') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($resepMasuk as $rm)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-4 bg-slate-50 border-b border-slate-100 flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-slate-900">{{ $rm->pasien->nama_lengkap }}</h3>
                    <p class="text-xs text-slate-500">{{ $rm->pasien->no_rekam_medis }}</p>
                </div>
                <span class="bg-white border border-slate-200 text-slate-600 text-xs px-2 py-1 rounded">
                    {{ $rm->poli->nama_poli }}
                </span>
            </div>
            <div class="p-4">
                <div class="text-sm text-slate-600 mb-4">
                    <p class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Dr. {{ $rm->dokter->pengguna->nama_lengkap }}
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $rm->created_at->format('H:i') }} WIB
                    </p>
                </div>
                <button wire:click="lihatResep({{ $rm->id }})" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-lg transition-colors">
                    Lihat & Proses Resep
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-slate-50 rounded-xl border border-dashed border-slate-300">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="mt-2 text-slate-500 font-medium">Tidak ada antrian resep saat ini.</p>
        </div>
        @endforelse
    </div>

    <!-- MODAL DETAIL RESEP -->
    @if($tampilkanModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="tutupModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-slate-900 mb-4" id="modal-title">
                        Detail Resep Obat
                    </h3>
                    
                    <div class="border rounded-lg overflow-hidden mb-6">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Nama Obat</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Dosis</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Stok Gudang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach($detailResepList as $d)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-slate-900">{{ $d->obat->nama_obat }}</td>
                                    <td class="px-4 py-2 text-sm text-slate-900 font-bold">{{ $d->jumlah }} {{ $d->obat->satuan }}</td>
                                    <td class="px-4 py-2 text-sm text-slate-600">{{ $d->dosis }}</td>
                                    <td class="px-4 py-2 text-sm {{ $d->obat->stok_saat_ini < $d->jumlah ? 'text-red-600 font-bold' : 'text-emerald-600' }}">
                                        {{ $d->obat->stok_saat_ini }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Menekan tombol "Selesai & Serahkan" akan otomatis mengurangi stok obat sesuai jumlah resep.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="prosesResep" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:w-auto sm:text-sm">
                        Selesai & Serahkan Obat
                    </button>
                    <button wire:click="tutupModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
