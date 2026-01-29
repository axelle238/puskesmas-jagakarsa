<div class="space-y-20 pb-20">
    
    <!-- Hero Section -->
    <section class="relative bg-medis-500 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-medis-600 to-medis-500 opacity-90"></div>
        <!-- Decorative Circle -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-24 md:py-32">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="text-white space-y-6">
                    <span class="inline-block py-1 px-3 rounded-full bg-medis-700 bg-opacity-50 border border-medis-400 text-sm font-semibold tracking-wide">
                        Selamat Datang di Puskesmas Jagakarsa
                    </span>
                    <h1 class="text-4xl md:text-6xl font-bold leading-tight">
                        Layanan Kesehatan <br>
                        <span class="text-medis-100">Modern & Terpadu</span>
                    </h1>
                    <p class="text-lg text-medis-50 max-w-lg leading-relaxed">
                        Kami menerapkan Integrasi Layanan Primer (ILP) untuk memastikan setiap siklus kehidupan mendapatkan perawatan kesehatan terbaik. Cepat, Mudah, dan Profesional.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="/antrian" wire:navigate class="px-8 py-4 bg-white text-medis-600 rounded-xl font-bold text-lg shadow-lg hover:bg-gray-100 hover:scale-105 transition transform text-center">
                            Ambil Antrian Online
                        </a>
                        <a href="#jadwal" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white hover:text-medis-600 transition text-center">
                            Lihat Jadwal
                        </a>
                    </div>
                </div>
                <div class="hidden md:block relative">
                    <!-- Placeholder Ilustrasi -->
                    <div class="w-full h-96 bg-white bg-opacity-10 rounded-2xl border-2 border-white border-opacity-20 flex items-center justify-center relative overflow-hidden shadow-2xl backdrop-blur-sm">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-white/20"></div>
                        <div class="text-center p-8">
                            <div class="text-6xl mb-4">🏥</div>
                            <h3 class="text-2xl font-bold text-white mb-2">Sehat Bersama Kami</h3>
                            <p class="text-white/80">Melayani setulus hati</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Layanan (ILP Clusters) -->
    <section id="layanan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-300">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-3xl mb-6">👶</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Ibu, Anak & Remaja</h3>
                <p class="text-gray-600 leading-relaxed mb-4">Layanan terpadu untuk kesehatan ibu hamil, balita, anak sekolah hingga remaja. Imunisasi, MTBS, dan konseling.</p>
                <span class="text-sm font-semibold text-blue-600">Klaster 2 ILP &rarr;</span>
            </div>
             <!-- Card 2 -->
             <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-300">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-3xl mb-6">👨‍👩‍👧</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Usia Dewasa & Lansia</h3>
                <p class="text-gray-600 leading-relaxed mb-4">Skrining penyakit tidak menular (PTM), kesehatan jiwa, dan pelayanan geriatri untuk kualitas hidup yang lebih baik.</p>
                <span class="text-sm font-semibold text-green-600">Klaster 3 ILP &rarr;</span>
            </div>
             <!-- Card 3 -->
             <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-300">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-3xl mb-6">🦠</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Penyakit Menular</h3>
                <p class="text-gray-600 leading-relaxed mb-4">Penanggulangan penyakit menular seperti TBC, HIV, dan infeksi lainnya dengan pengawasan ketat dan privasi terjaga.</p>
                <span class="text-sm font-semibold text-purple-600">Klaster 4 ILP &rarr;</span>
            </div>
        </div>
    </section>

    <!-- Daftar Poli -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Poli & Unit Layanan</h2>
            <p class="text-gray-500 mt-2">Kami menyediakan berbagai layanan medis spesifik</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($daftarPoli as $poli)
            <div class="bg-white border border-gray-200 p-6 rounded-xl hover:border-medis-400 hover:bg-medis-50 transition cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl group-hover:scale-110 transition">🩺</span>
                    <span class="text-xs font-bold bg-gray-100 text-gray-600 py-1 px-2 rounded">{{ $poli->tindakan_count }} Layanan</span>
                </div>
                <h4 class="text-lg font-bold text-gray-800 group-hover:text-medis-600">{{ $poli->nama_poli }}</h4>
                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $poli->deskripsi }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Jadwal Dokter -->
    <section id="jadwal" class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Jadwal Praktik Dokter</h2>
                    <p class="text-gray-500 mt-2">Cek jadwal dokter favorit Anda sebelum berkunjung</p>
                </div>
                <a href="/jadwal" class="text-medis-600 font-semibold hover:underline mt-4 md:mt-0">Lihat Selengkapnya &rarr;</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @foreach(['Senin', 'Selasa', 'Rabu'] as $hari)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-medis-600 text-white py-3 px-6 font-bold text-lg flex justify-between items-center">
                        {{ $hari }}
                        <span class="text-xs bg-white/20 py-1 px-2 rounded font-normal">Hari Ini</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @if(isset($jadwalDokter[$hari]))
                            @foreach($jadwalDokter[$hari]->take(3) as $jadwal)
                            <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-lg">
                                    👨‍⚕️
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-800 text-sm">{{ $jadwal->dokter->pengguna->nama_lengkap ?? 'Dokter' }}</h5>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                        <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded">{{ $jadwal->poli->nama_poli }}</span>
                                        <span>🕒 {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="p-6 text-center text-gray-400 text-sm">Tidak ada jadwal</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Daftar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-900 rounded-3xl p-8 md:p-16 flex flex-col md:flex-row items-center justify-between gap-10 overflow-hidden relative">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-medis-500 rounded-full blur-3xl opacity-20"></div>
            
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Jangan Tunggu Sakit Parah</h2>
                <p class="text-gray-400 text-lg max-w-xl">Kesehatan adalah aset paling berharga. Lakukan skrining kesehatan rutin atau daftar berobat sekarang juga tanpa antri lama.</p>
            </div>
            <div class="relative z-10 flex flex-col gap-4 w-full md:w-auto">
                <a href="/antrian" class="px-8 py-4 bg-medis-500 hover:bg-medis-600 text-white rounded-xl font-bold text-center shadow-lg transition transform hover:-translate-y-1">
                    Daftar Sekarang
                </a>
                <span class="text-gray-500 text-sm text-center">Butuh akun untuk riwayat medis lengkap</span>
            </div>
        </div>
    </section>
</div>