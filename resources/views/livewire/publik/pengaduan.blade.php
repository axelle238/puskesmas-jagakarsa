<div class="py-12 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-slate-900 mb-2">Layanan Pengaduan & Aspirasi</h1>
            <p class="text-slate-600">Sampaikan keluhan, kritik, dan saran Anda untuk pelayanan yang lebih baik.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
            @if(session('sukses'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <div>
                        <p class="font-bold">Terkirim!</p>
                        <p class="text-sm">{{ session('sukses') }}</p>
                    </div>
                </div>
            @endif

            <form wire:submit="kirim" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" wire:model="nama_pelapor" class="w-full rounded-xl border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Nama Anda">
                        @error('nama_pelapor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon / WA</label>
                        <input type="text" wire:model="no_telepon" class="w-full rounded-xl border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0812...">
                        @error('no_telepon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email (Opsional)</label>
                        <input type="email" wire:model="email" class="w-full rounded-xl border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="email@contoh.com">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Laporan</label>
                        <select wire:model="kategori" class="w-full rounded-xl border-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="Layanan Medis">Layanan Medis (Dokter/Perawat)</option>
                            <option value="Fasilitas">Fasilitas & Kebersihan</option>
                            <option value="Administrasi">Administrasi & Pendaftaran</option>
                            <option value="Farmasi">Obat & Farmasi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Isi Pengaduan</label>
                    <textarea wire:model="isi_laporan" rows="5" class="w-full rounded-xl border-slate-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Jelaskan kronologi kejadian secara detail..."></textarea>
                    @error('isi_laporan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Foto (Opsional)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-sm text-slate-500"><span class="font-bold">Klik untuk upload</span> atau drag and drop</p>
                            </div>
                            <input type="file" wire:model="bukti_foto" class="hidden" />
                        </label>
                    </div>
                    @if($bukti_foto)
                        <p class="text-sm text-emerald-600 mt-2 font-bold">Foto berhasil dipilih.</p>
                    @endif
                    @error('bukti_foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-slate-800 transition transform hover:scale-[1.02]">
                        Kirim Pengaduan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
