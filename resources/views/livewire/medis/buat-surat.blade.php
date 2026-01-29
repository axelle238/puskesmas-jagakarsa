<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Buat Surat Keterangan</h1>
            <p class="text-slate-500">Layanan administrasi surat sakit, sehat, dan rujukan.</p>
        </div>
        @if($modeCetak)
            <button wire:click="resetForm" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg font-bold text-sm">
                Buat Baru
            </button>
        @endif
    </div>

    @if(!$modeCetak)
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <!-- Pencarian Pasien -->
        <div class="mb-8 border-b border-slate-100 pb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Cari Pasien (NIK / No. RM)</label>
            <div class="flex gap-2">
                <input type="text" wire:model="nik" wire:keydown.enter="cariPasien" class="w-full md:w-1/3 rounded-lg border-slate-300" placeholder="Masukkan NIK atau RM...">
                <button wire:click="cariPasien" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold">Cari</button>
            </div>
            @error('nik') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            @if(session('error')) <span class="text-red-500 text-xs mt-1 block font-bold">{{ session('error') }}</span> @endif

            @if($pasien)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-4">
                    <div class="bg-blue-200 text-blue-700 w-10 h-10 rounded-full flex items-center justify-center font-bold">
                        {{ substr($pasien->nama_lengkap, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">{{ $pasien->nama_lengkap }}</p>
                        <p class="text-xs text-slate-600">RM: {{ $pasien->no_rekam_medis }} | Usia: {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Th</p>
                    </div>
                </div>
            @endif
        </div>

        @if($pasien)
        <!-- Form Surat -->
        <form wire:submit="simpanSurat">
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Surat</label>
                <div class="flex gap-4">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer {{ $jenis_surat == 'sakit' ? 'bg-blue-50 border-blue-500' : '' }}">
                        <input type="radio" wire:model.live="jenis_surat" value="sakit" class="text-blue-600">
                        <span class="ml-2 font-medium">Surat Keterangan Sakit</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer {{ $jenis_surat == 'sehat' ? 'bg-blue-50 border-blue-500' : '' }}">
                        <input type="radio" wire:model.live="jenis_surat" value="sehat" class="text-blue-600">
                        <span class="ml-2 font-medium">Surat Keterangan Sehat</span>
                    </label>
                </div>
            </div>

            @if($jenis_surat == 'sakit')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Mulai Tanggal</label>
                        <input type="date" wire:model="tanggal_mulai" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Lama Istirahat (Hari)</label>
                        <input type="number" wire:model="lama_istirahat" class="w-full rounded-lg border-slate-300">
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Berat Badan (kg)</label>
                        <input type="number" wire:model="bb" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Tinggi Badan (cm)</label>
                        <input type="number" wire:model="tb" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Tekanan Darah</label>
                        <input type="text" wire:model="tensi" class="w-full rounded-lg border-slate-300" placeholder="120/80">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Buta Warna</label>
                        <select wire:model="buta_warna" class="w-full rounded-lg border-slate-300">
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-sm font-bold mb-1">Keperluan Surat</label>
                        <textarea wire:model="keperluan" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Melamar Pekerjaan"></textarea>
                    </div>
                </div>
            @endif

            <div class="flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg">
                    Simpan & Cetak
                </button>
            </div>
        </form>
        @endif
    </div>
    @endif

    <!-- PREVIEW CETAK -->
    @if($modeCetak && $suratBaru)
    <div class="bg-slate-500 p-8 rounded-xl flex justify-center">
        <div class="bg-white w-[210mm] min-h-[297mm] p-12 shadow-2xl relative" id="areaCetak">
            
            <!-- KOP SURAT -->
            <div class="flex items-center border-b-2 border-double border-black pb-4 mb-8">
                <img src="{{ asset('logo-puskesmas.svg') }}" class="w-20 h-20 mr-6">
                <div class="text-center flex-1">
                    <h2 class="text-xl font-bold uppercase">Pemerintah Provinsi DKI Jakarta</h2>
                    <h1 class="text-2xl font-black uppercase">Puskesmas Kecamatan Jagakarsa</h1>
                    <p class="text-sm">Jl. Moh. Kahfi 1 No. 17, Jagakarsa, Jakarta Selatan. Telp: (021) 786-xxxx</p>
                </div>
                <div class="w-20"></div> <!-- Spacer -->
            </div>

            <!-- ISI SURAT -->
            <div class="text-center mb-8">
                <h3 class="text-lg font-bold uppercase underline">SURAT KETERANGAN {{ $suratBaru->jenis_surat == 'sakit' ? 'SAKIT' : 'SEHAT' }}</h3>
                <p>Nomor: {{ $suratBaru->no_surat }}</p>
            </div>

            <p class="mb-4">Yang bertanda tangan di bawah ini Dokter Pemeriksa Puskesmas Kecamatan Jagakarsa, menerangkan bahwa:</p>

            <table class="w-full mb-6 ml-4">
                <tr><td class="w-40 py-1">Nama</td><td>: <strong>{{ $pasien->nama_lengkap }}</strong></td></tr>
                <tr><td class="w-40 py-1">Umur</td><td>: {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun</td></tr>
                <tr><td class="w-40 py-1">Alamat</td><td>: {{ $pasien->alamat_lengkap }}</td></tr>
            </table>

            @if($suratBaru->jenis_surat == 'sakit')
                <p class="mb-4 text-justify">
                    Berdasarkan hasil pemeriksaan medis, pasien tersebut dalam keadaan sakit dan perlu beristirahat selama 
                    <strong>{{ $suratBaru->lama_istirahat }} ({{ terbilang($suratBaru->lama_istirahat) }}) hari</strong>, 
                    terhitung mulai tanggal <strong>{{ $suratBaru->tanggal_mulai->format('d F Y') }}</strong> sampai dengan 
                    <strong>{{ $suratBaru->tanggal_mulai->addDays($suratBaru->lama_istirahat - 1)->format('d F Y') }}</strong>.
                </p>
            @else
                <p class="mb-4">Telah dilakukan pemeriksaan fisik dengan hasil sebagai berikut:</p>
                <table class="w-full mb-6 ml-4 border border-slate-300">
                    <tr class="border-b"><td class="p-2 w-1/2">Berat Badan</td><td class="p-2 font-bold">{{ $suratBaru->catatan_fisik['bb'] }} kg</td></tr>
                    <tr class="border-b"><td class="p-2">Tinggi Badan</td><td class="p-2 font-bold">{{ $suratBaru->catatan_fisik['tb'] }} cm</td></tr>
                    <tr class="border-b"><td class="p-2">Tekanan Darah</td><td class="p-2 font-bold">{{ $suratBaru->catatan_fisik['tensi'] }} mmHg</td></tr>
                    <tr><td class="p-2">Buta Warna</td><td class="p-2 font-bold">{{ $suratBaru->catatan_fisik['buta_warna'] }}</td></tr>
                </table>
                <p class="mb-4">
                    Surat keterangan ini dibuat untuk keperluan: <strong>{{ $suratBaru->keperluan }}</strong>.
                </p>
            @endif

            <p class="mb-12">Demikian surat keterangan ini dibuat untuk dapat dipergunakan semestinya.</p>

            <!-- TTD -->
            <div class="flex justify-end">
                <div class="text-center">
                    <p class="mb-20">Jakarta, {{ date('d F Y') }}<br>Dokter Pemeriksa,</p>
                    <p class="font-bold border-b border-black inline-block">{{ auth()->user()->nama_lengkap }}</p>
                    <p>SIP: {{ auth()->user()->pegawai->sip ?? '-' }}</p>
                </div>
            </div>

            <!-- Tombol Aksi (Hidden saat print) -->
            <div class="absolute top-4 right-4 print:hidden flex gap-2">
                <button onclick="window.print()" class="bg-blue-600 text-white p-2 rounded shadow hover:bg-blue-700" title="Cetak">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                </button>
                <button wire:click="$set('modeCetak', false)" class="bg-red-500 text-white p-2 rounded shadow hover:bg-red-600" title="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #areaCetak, #areaCetak * {
                visibility: visible;
            }
            #areaCetak {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                box-shadow: none;
            }
            /* Hilangkan elemen layout admin */
            nav, aside, header, footer { display: none !important; }
        }
    </style>
    
    <!-- Helper PHP untuk Terbilang (Sederhana) -->
    @php
    function terbilang($x) {
        $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if ($x < 12) return $angka[$x];
        elseif ($x < 20) return $angka[$x - 10] . " belas";
        elseif ($x < 100) return $angka[$x / 10] . " puluh " . $angka[$x % 10];
        return $x; // Cukup sampai puluhan untuk hari sakit
    }
    @endphp
    @endif
</div>
