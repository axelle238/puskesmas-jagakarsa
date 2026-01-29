<div class="min-h-screen bg-slate-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="max-w-3xl mx-auto text-center mb-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Ambil Antrian Online</h1>
            <p class="text-slate-500">Dapatkan nomor antrian tanpa perlu menunggu lama di loket.</p>
        </div>

        <!-- Wizard Steps Indicator -->
        <div class="max-w-3xl mx-auto mb-12">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 -z-10"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-emerald-500 -z-10 transition-all duration-500" style="width: {{ ($tahap - 1) * 33.33 }}%"></div>
                
                <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 1 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">1</div>
                    <span class="text-xs font-medium {{ $tahap >= 1 ? 'text-emerald-700' : 'text-slate-400' }}">Data Diri</span>
                </div>
                <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 2 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">2</div>
                    <span class="text-xs font-medium {{ $tahap >= 2 ? 'text-emerald-700' : 'text-slate-400' }}">Pilih Poli</span>
                </div>
                <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 3 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">3</div>
                    <span class="text-xs font-medium {{ $tahap >= 3 ? 'text-emerald-700' : 'text-slate-400' }}">Konfirmasi</span>
                </div>
                <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">4</div>
                    <span class="text-xs font-medium {{ $tahap >= 4 ? 'text-emerald-700' : 'text-slate-400' }}">Selesai</span>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
            
            <!-- TAHAP 1: IDENTIFIKASI -->
            @if($tahap == 1)
            <div class="p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    Identifikasi Pasien
                </h2>
                
                @if(!$mode_registrasi_baru)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Masukkan NIK (Nomor KTP)</label>
                        <input type="text" wire:model="nik" class="w-full px-4 py-3 rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-lg" placeholder="16 Digit NIK" maxlength="16">
                        @error('nik') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <button wire:click="cekNik" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="cekNik">Cek Data Pasien</span>
                        <span wire:loading wire:target="cekNik">Sedang Memeriksa...</span>
                        <svg wire:loading.remove wire:target="cekNik" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    
                    @if(session()->has('error'))
                        <div class="mt-4 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                @else
                    <!-- Form Registrasi Cepat -->
                    <div class="bg-blue-50 p-6 rounded-xl mb-6 border border-blue-100">
                        <div class="flex items-start gap-3 mb-4">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="font-bold text-blue-900">Data Pasien Belum Ditemukan</h3>
                                <p class="text-sm text-blue-700">Silakan isi data berikut untuk pendaftaran cepat.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">NIK</label>
                                <input type="text" wire:model="nik" class="w-full rounded-lg border-slate-300 bg-slate-100 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" wire:model="nama_lengkap" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Sesuai KTP">
                                @error('nama_lengkap') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Handphone (WhatsApp)</label>
                                <input type="number" wire:model="no_hp" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="08...">
                                @error('no_hp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <button wire:click="$set('mode_registrasi_baru', false)" class="w-1/3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold py-3 rounded-xl">Batal</button>
                        <button wire:click="daftarBaru" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="daftarBaru">Daftar & Lanjut</span>
                            <span wire:loading wire:target="daftarBaru">Menyimpan...</span>
                        </button>
                    </div>
                @endif
            </div>
            @endif

            <!-- TAHAP 2: PILIH LAYANAN -->
            @if($tahap == 2)
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Pilih Jadwal Dokter</h2>
                    <div class="text-right">
                        <p class="text-sm text-slate-500">Pasien:</p>
                        <p class="font-bold text-emerald-700">{{ $pasien_ditemukan['nama_lengkap'] }}</p>
                    </div>
                </div>

                @if(count($jadwalTersedia) > 0)
                    <div class="space-y-4">
                        @foreach($jadwalTersedia as $jadwal)
                        <div class="border border-slate-200 rounded-xl p-4 hover:border-emerald-500 cursor-pointer transition-all flex items-center justify-between group"
                             wire:click="pilihJadwal({{ $jadwal->id }})">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center font-bold text-lg">
                                    {{ substr($jadwal->poli->nama_poli, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-emerald-700">{{ $jadwal->poli->nama_poli }}</h3>
                                    <p class="text-sm text-slate-500">{{ $jadwal->dokter->pengguna->nama_lengkap }}</p>
                                    <p class="text-xs text-slate-400 mt-1">Jam: {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">Pilih</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-slate-500 font-medium">Tidak ada jadwal dokter tersedia hari ini.</p>
                        <p class="text-xs text-slate-400">Silakan coba kembali besok atau hubungi loket.</p>
                    </div>
                @endif

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <button wire:click="kembali" class="text-slate-500 hover:text-slate-800 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </button>
                </div>
            </div>
            @endif

            <!-- TAHAP 3: KONFIRMASI -->
            @if($tahap == 3)
            <div class="p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6 text-center">Konfirmasi Antrian</h2>
                
                <div class="bg-slate-50 rounded-xl p-6 space-y-4 mb-8">
                    <div class="flex justify-between border-b border-slate-200 pb-4">
                        <span class="text-slate-500">Nama Pasien</span>
                        <span class="font-bold text-slate-900">{{ $pasien_ditemukan['nama_lengkap'] }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-4">
                        <span class="text-slate-500">NIK</span>
                        <span class="font-mono text-slate-900">{{ $pasien_ditemukan['nik'] }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-4">
                        <span class="text-slate-500">Layanan Tujuan</span>
                        @php $jadwal = \App\Models\JadwalDokter::find($id_jadwal_dipilih); @endphp
                        <span class="font-bold text-emerald-700">{{ $jadwal->poli->nama_poli }}</span>
                    </div>
                    <div class="flex justify-between pb-2">
                        <span class="text-slate-500">Dokter</span>
                        <span class="font-medium text-slate-900">{{ $jadwal->dokter->pengguna->nama_lengkap }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tanggal</span>
                        <span class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($tanggal_kunjungan)->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button wire:click="kembali" class="w-1/3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold py-3 rounded-xl">Kembali</button>
                    <button wire:click="prosesAntrian" class="w-2/3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="prosesAntrian">Ambil Antrian Sekarang</span>
                        <span wire:loading wire:target="prosesAntrian">Memproses...</span>
                    </button>
                </div>
                
                @if(session()->has('error'))
                    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded text-center text-sm">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            @endif

            <!-- TAHAP 4: SUKSES / TIKET -->
            @if($tahap == 4)
            <div class="p-8 text-center bg-emerald-50">
                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Berhasil Terdaftar!</h2>
                <p class="text-slate-600 mb-8">Silakan simpan bukti antrian ini.</p>

                <div class="bg-white p-8 rounded-2xl shadow-lg border border-slate-200 max-w-sm mx-auto mb-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                    
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Antrian</p>
                    <div class="text-5xl font-black text-slate-900 mb-4 tracking-tight">{{ $tiket_antrian['nomor_antrian'] }}</div>
                    
                    <div class="border-t border-dashed border-slate-200 my-4"></div>
                    
                    <div class="text-left space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Poli:</span>
                            <span class="font-bold text-slate-800">{{ $tiket_antrian->poli->nama_poli }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Dokter:</span>
                            <span class="font-bold text-slate-800">{{ $tiket_antrian->jadwal->dokter->pengguna->nama_lengkap }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Estimasi:</span>
                            <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($tiket_antrian->waktu_checkin)->format('H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 max-w-sm mx-auto">
                    <button onclick="window.print()" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Tiket
                    </button>
                    <a href="/" wire:navigate class="w-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold py-3 rounded-xl block">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
