<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Pembuatan Surat Keterangan Dokter</h1>

    @if(session('sukses'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex justify-between items-center">
            <span>{{ session('sukses') }}</span>
            <button onclick="window.print()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Cetak Surat</button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Cari Pasien -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 h-fit">
            <h3 class="font-bold mb-4">1. Cari Pasien</h3>
            <div class="flex gap-2 mb-4">
                <input type="text" wire:model="nik" class="w-full rounded-lg border-slate-300" placeholder="Masukkan NIK">
                <button wire:click="cariPasien" class="bg-blue-600 text-white px-3 py-2 rounded-lg">Cari</button>
            </div>
            @if($pasien)
                <div class="bg-blue-50 p-3 rounded-lg text-sm">
                    <p class="font-bold">{{ $pasien->nama_lengkap }}</p>
                    <p>{{ $pasien->nik }}</p>
                    <p>{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun</p>
                </div>
            @endif
            @if(session('error')) <p class="text-red-500 text-sm mt-2">{{ session('error') }}</p> @endif
        </div>

        <!-- Form Surat -->
        <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h3 class="font-bold mb-4">2. Detail Surat</h3>
            
            @if($pasien)
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Jenis Surat</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 border p-3 rounded-lg cursor-pointer {{ $jenis_surat == 'sakit' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200' }}">
                            <input type="radio" wire:model.live="jenis_surat" value="sakit" class="text-emerald-600">
                            <span>Surat Sakit (Sick Leave)</span>
                        </label>
                        <label class="flex items-center gap-2 border p-3 rounded-lg cursor-pointer {{ $jenis_surat == 'sehat' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200' }}">
                            <input type="radio" wire:model.live="jenis_surat" value="sehat" class="text-emerald-600">
                            <span>Surat Sehat (Health Cert)</span>
                        </label>
                    </div>
                </div>

                @if($jenis_surat == 'sakit')
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Mulai Tanggal</label>
                            <input type="date" wire:model="tanggal_mulai" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Lama Istirahat (Hari)</label>
                            <input type="number" wire:model="lama_istirahat" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Berat Badan (Kg)</label>
                            <input type="number" wire:model="bb" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Tinggi Badan (cm)</label>
                            <input type="number" wire:model="tb" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Tekanan Darah</label>
                            <input type="text" wire:model="tensi" class="w-full rounded-lg border-slate-300" placeholder="120/80">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Buta Warna</label>
                            <select wire:model="buta_warna" class="w-full rounded-lg border-slate-300">
                                <option value="Tidak">Tidak</option>
                                <option value="Ya">Ya</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm text-slate-600 mb-1">Keperluan</label>
                            <textarea wire:model="keperluan" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Melamar Pekerjaan"></textarea>
                        </div>
                    </div>
                @endif

                <button wire:click="simpanSurat" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg">
                    Simpan & Generate Surat
                </button>
            @else
                <div class="text-center py-12 text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-300">
                    Silakan cari pasien terlebih dahulu.
                </div>
            @endif
        </div>
    </div>

    <!-- PREVIEW CETAK (Hidden on screen, Visible on print) -->
    @if($suratBaru)
    <div class="hidden print:block fixed inset-0 bg-white z-[100] p-12">
        <div class="text-center border-b-2 border-black pb-4 mb-6">
            <h1 class="text-2xl font-bold uppercase">Puskesmas Jagakarsa</h1>
            <p>Jl. Moh. Kahfi 1, Jagakarsa, Jakarta Selatan</p>
            <p class="text-sm">Telp: (021) 786-xxxx | Email: info@puskesmas-jagakarsa.go.id</p>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-xl font-bold underline mb-1">SURAT KETERANGAN {{ $suratBaru->jenis_surat == 'sakit' ? 'SAKIT' : 'SEHAT' }}</h2>
            <p>Nomor: {{ $suratBaru->no_surat }}</p>
        </div>

        <p class="mb-4">Yang bertanda tangan di bawah ini, Dokter Puskesmas Jagakarsa menerangkan bahwa:</p>

        <table class="w-full mb-6">
            <tr><td class="w-40 py-1">Nama</td><td>: {{ $pasien->nama_lengkap }}</td></tr>
            <tr><td class="w-40 py-1">Umur</td><td>: {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun</td></tr>
            <tr><td class="w-40 py-1">Jenis Kelamin</td><td>: {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
            <tr><td class="w-40 py-1">Alamat</td><td>: {{ $pasien->alamat_lengkap }}</td></tr>
        </table>

        @if($suratBaru->jenis_surat == 'sakit')
            <p class="mb-4 text-justify">
                Berdasarkan hasil pemeriksaan medis, pasien tersebut sedang dalam keadaan sakit dan perlu beristirahat selama 
                <strong>{{ $suratBaru->lama_istirahat }} hari</strong> terhitung mulai tanggal 
                <strong>{{ \Carbon\Carbon::parse($suratBaru->tanggal_mulai)->format('d M Y') }}</strong>.
            </p>
        @else
            <p class="mb-4">Berdasarkan pemeriksaan fisik dengan hasil:</p>
            <ul class="list-disc pl-8 mb-4">
                <li>Berat Badan: {{ $suratBaru->catatan_fisik['bb'] ?? '-' }} Kg</li>
                <li>Tinggi Badan: {{ $suratBaru->catatan_fisik['tb'] ?? '-' }} cm</li>
                <li>Tekanan Darah: {{ $suratBaru->catatan_fisik['tensi'] ?? '-' }} mmHg</li>
                <li>Buta Warna: {{ $suratBaru->catatan_fisik['buta_warna'] ?? '-' }}</li>
            </ul>
            <p class="mb-4">Dinyatakan <strong>SEHAT</strong> untuk keperluan: {{ $suratBaru->keperluan }}</p>
        @endif

        <div class="flex justify-end mt-12">
            <div class="text-center">
                <p>Jakarta, {{ date('d F Y') }}</p>
                <p class="mb-20">Dokter Pemeriksa,</p>
                <p class="font-bold underline">{{ auth()->user()->nama_lengkap }}</p>
                <p>SIP. {{ auth()->user()->pegawai->sip ?? '-' }}</p>
            </div>
        </div>
    </div>
    @endif
</div>