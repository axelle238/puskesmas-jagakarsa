<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Administrasi Surat Medis</h1>
    </div>

    @if (session()->has('pesan'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('pesan') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Pencarian Pasien -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative">
                <h3 class="font-bold text-gray-700 mb-4">1. Pilih Pasien</h3>
                
                @if(!$pasien_terpilih)
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="cari_pasien" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" placeholder="Ketik Nama atau No. RM Pasien...">
                        @if(!empty($hasil_pencarian))
                            <div class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                                @foreach($hasil_pencarian as $p)
                                    <div wire:click="pilihPasien({{ $p->id }})" class="p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-0">
                                        <div class="font-bold text-gray-800">{{ $p->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $p->no_rekam_medis }} - {{ \Carbon\Carbon::parse($p->tanggal_lahir)->age }} Thn</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex items-center justify-between bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div>
                            <div class="font-bold text-blue-800 text-lg">{{ $pasien_terpilih->nama_lengkap }}</div>
                            <div class="text-sm text-blue-600">{{ $pasien_terpilih->no_rekam_medis }} | {{ $pasien_terpilih->alamat_lengkap }}</div>
                        </div>
                        <button wire:click="$set('pasien_terpilih', null)" class="text-red-500 hover:text-red-700 font-bold text-sm">Ganti</button>
                    </div>
                @endif
            </div>

            <!-- Detail Surat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-700 mb-4">2. Detail Surat</h3>
                
                <form wire:submit="simpanSurat" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat</label>
                        <select wire:model.live="jenis_surat" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                            <option value="Surat Keterangan Sakit">Surat Keterangan Sakit</option>
                            <option value="Surat Keterangan Sehat">Surat Keterangan Sehat</option>
                            <option value="Rujukan">Surat Rujukan RS</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemeriksaan</label>
                        <input type="date" wire:model="tanggal_mulai" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                    </div>

                    <!-- Input Kondisional -->
                    @if($jenis_surat == 'Surat Keterangan Sakit')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lama Istirahat (Hari)</label>
                            <input type="number" wire:model="lama_istirahat" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500">
                        </div>
                    @endif

                    @if($jenis_surat == 'Surat Keterangan Sehat')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Fisik (TB/BB/Buta Warna)</label>
                            <textarea wire:model="keterangan_tambahan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Contoh: TB: 170cm, BB: 65kg, Tidak Buta Warna"></textarea>
                        </div>
                    @endif

                    @if($jenis_surat == 'Rujukan')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RS Tujuan Rujukan</label>
                            <input type="text" wire:model="tujuan_rujukan" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500" placeholder="Nama Rumah Sakit...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosa Sementara</label>
                            <textarea wire:model="keterangan_tambahan" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500"></textarea>
                        </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow transition disabled:opacity-50" {{ !$pasien_terpilih ? 'disabled' : '' }}>
                            📝 Terbitkan Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-700 mb-4">Riwayat Surat Terakhir</h3>
                <div class="space-y-4">
                    @forelse($riwayatSurat as $surat)
                        <div class="border-b border-gray-100 pb-3 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-sm text-gray-800">{{ $surat->jenis_surat }}</div>
                                    <div class="text-xs text-gray-500">{{ $surat->pasien->nama_lengkap }}</div>
                                </div>
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-mono">{{ $surat->no_surat }}</span>
                            </div>
                            <div class="mt-2 text-right">
                                <button onclick="printSurat('surat-{{ $surat->id }}')" class="text-blue-600 text-xs hover:underline font-bold">🖨️ Cetak Ulang</button>
                            </div>

                            <!-- Layout Cetak Hidden -->
                            <div id="surat-{{ $surat->id }}" class="hidden print:block print:visible p-12 bg-white text-black font-serif leading-relaxed">
                                <div class="text-center border-b-4 border-double border-black pb-4 mb-8">
                                    <h2 class="text-2xl font-bold uppercase">Puskesmas Jagakarsa</h2>
                                    <p>Jl. Moh. Kahfi 1, Jagakarsa, Jakarta Selatan</p>
                                </div>
                                
                                <h3 class="text-center font-bold text-xl uppercase underline mb-8">{{ $surat->jenis_surat }}</h3>
                                
                                <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
                                <table class="w-full my-4">
                                    <tr><td class="w-32 py-1">Nama</td><td>: <strong>{{ $surat->pasien->nama_lengkap }}</strong></td></tr>
                                    <tr><td class="w-32 py-1">Umur</td><td>: {{ \Carbon\Carbon::parse($surat->pasien->tanggal_lahir)->age }} Tahun</td></tr>
                                    <tr><td class="w-32 py-1">Alamat</td><td>: {{ $surat->pasien->alamat_lengkap }}</td></tr>
                                </table>

                                @if($surat->jenis_surat == 'Surat Keterangan Sakit')
                                    <p>Perlu beristirahat karena sakit selama <strong>{{ $surat->lama_istirahat }} hari</strong>, terhitung mulai tanggal {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->isoFormat('D MMMM Y') }}.</p>
                                @elseif($surat->jenis_surat == 'Surat Keterangan Sehat')
                                    <p>Telah diperiksa kesehatannya dan dinyatakan <strong>SEHAT</strong>.</p>
                                    <p class="mt-2 text-sm">{{ $surat->keterangan_tambahan }}</p>
                                @elseif($surat->jenis_surat == 'Rujukan')
                                    <p>Dirujuk ke <strong>{{ $surat->tujuan_rujukan }}</strong>.</p>
                                    <p class="mt-2">Diagnosa: {{ $surat->keterangan_tambahan }}</p>
                                @endif

                                <div class="mt-16 text-right">
                                    <p>Jakarta, {{ \Carbon\Carbon::parse($surat->created_at)->isoFormat('D MMMM Y') }}</p>
                                    <p class="mb-20">Dokter Pemeriksa,</p>
                                    <p class="font-bold underline">{{ Auth::user()->nama_lengkap }}</p>
                                    <p class="text-sm">SIP: {{ \App\Models\Pegawai::where('id_pengguna', Auth::id())->first()->sip ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-sm">Belum ada surat dibuat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function printSurat(elementId) {
            const printContent = document.getElementById(elementId).innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload(); 
        }
    </script>
</div>
