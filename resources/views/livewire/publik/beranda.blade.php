<div>
    <!-- Hero Section -->
    <div class="relative bg-slate-900 overflow-hidden">
        <!-- Background Image/Pattern -->
        <div class="absolute inset-0">
            @if($profil->hero_image)
                <img src="{{ asset('storage/' . $profil->hero_image) }}" class="w-full h-full object-cover opacity-30">
            @else
                <div class="w-full h-full bg-gradient-to-br from-slate-900 to-emerald-900 opacity-90"></div>
                <!-- Pattern Overlay -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 32px 32px;"></div>
            @endif
        </div>

        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="max-w-2xl">
                <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-sm font-bold tracking-wider mb-6">
                    PUSKESMAS KECAMATAN JAGAKARSA
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">
                    {{ $profil->hero_title }}
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-8 leading-relaxed">
                    {{ $profil->hero_subtitle }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/antrian-online" wire:navigate class="bg-emerald-600 hover:bg-emerald-500 text-white px-8 py-4 rounded-xl font-bold text-center transition shadow-lg shadow-emerald-900/50 flex items-center justify-center gap-2">
                        <span>Ambil Antrian</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                    <a href="#layanan" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-8 py-4 rounded-xl font-bold text-center transition border border-white/20">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Bar (Jadwal/Lokasi) -->
    <div class="bg-white border-b border-slate-100 relative z-20 -mt-8 mx-4 md:mx-auto max-w-6xl rounded-2xl shadow-xl grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100">
        <div class="p-6 flex items-start gap-4">
            <div class="bg-blue-50 p-3 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900">Jam Operasional</h3>
                <p class="text-sm text-slate-500 mt-1">Senin - Jumat: 07:30 - 16:00<br>IGD 24 Jam</p>
            </div>
        </div>
        <div class="p-6 flex items-start gap-4">
            <div class="bg-emerald-50 p-3 rounded-lg text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900">Lokasi Kami</h3>
                <p class="text-sm text-slate-500 mt-1">Jl. Moh. Kahfi 1 No.17<br>Jagakarsa, Jakarta Selatan</p>
            </div>
        </div>
        <div class="p-6 flex items-start gap-4">
            <div class="bg-purple-50 p-3 rounded-lg text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900">Kontak Darurat</h3>
                <p class="text-sm text-slate-500 mt-1">(021) 786-xxxx<br>Siap melayani 24/7</p>
            </div>
        </div>
    </div>

    <!-- Sambutan -->
    <div class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="w-full md:w-1/2">
                    <div class="relative">
                        <div class="absolute -top-4 -left-4 w-24 h-24 bg-emerald-100 rounded-full z-0"></div>
                        <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-slate-100 rounded-full z-0"></div>
                        @if($profil->foto_kepala)
                            <img src="{{ asset('storage/' . $profil->foto_kepala) }}" class="relative z-10 rounded-2xl shadow-2xl w-full max-w-md mx-auto transform -rotate-2">
                        @else
                            <div class="relative z-10 rounded-2xl shadow-2xl w-full max-w-md mx-auto bg-slate-200 h-96 flex items-center justify-center text-slate-400">
                                Foto Kepala Puskesmas
                            </div>
                        @endif
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <h2 class="text-sm font-bold text-emerald-600 uppercase tracking-widest mb-2">Sambutan Kepala Puskesmas</h2>
                    <h3 class="text-3xl font-black text-slate-900 mb-6 leading-tight">Mewujudkan Masyarakat Jagakarsa Sehat dan Mandiri</h3>
                    <div class="prose prose-slate text-slate-600 mb-6">
                        <p>{{ $profil->sambutan_kepala }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-1 bg-emerald-500 rounded-full"></div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg">{{ $profil->nama_kepala_puskesmas }}</p>
                            <p class="text-slate-500 text-sm">Kepala Puskesmas Jagakarsa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layanan (Grid) -->
    <div id="layanan" class="py-20 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-black text-slate-900 mb-4">Layanan Kesehatan</h2>
                <p class="text-slate-600">Kami menyediakan berbagai poli dan layanan penunjang untuk kebutuhan kesehatan Anda dan keluarga.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($layanan as $l)
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                    <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $l->nama_poli }}</h3>
                    <p class="text-slate-500 text-sm mb-4">Layanan profesional dengan dokter berpengalaman di bidangnya.</p>
                    <a href="#" class="inline-flex items-center text-emerald-600 font-bold text-sm hover:text-emerald-700">
                        Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block border-2 border-slate-900 text-slate-900 px-8 py-3 rounded-xl font-bold hover:bg-slate-900 hover:text-white transition">Lihat Semua Layanan</a>
            </div>
        </div>
    </div>

    <!-- Artikel Terbaru -->
    <div id="artikel" class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 mb-2">Info & Edukasi</h2>
                    <p class="text-slate-600">Berita terbaru dan tips kesehatan untuk Anda.</p>
                </div>
                <a href="#" class="hidden md:inline-block text-emerald-600 font-bold hover:underline">Lihat Semua Artikel</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($artikel as $a)
                <div class="group cursor-pointer">
                    <div class="relative h-48 rounded-xl overflow-hidden mb-4">
                        @if($a->gambar_sampul)
                            <img src="{{ asset('storage/' . $a->gambar_sampul) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">No Image</div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur text-xs font-bold px-3 py-1 rounded-full text-slate-900 shadow-sm">{{ $a->kategori }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mb-2">{{ $a->created_at->format('d M Y') }}</p>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-emerald-600 transition">{{ $a->judul }}</h3>
                    <p class="text-sm text-slate-600 line-clamp-2">{{ $a->ringkasan }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CTA Daftar -->
    <div class="py-20 bg-slate-900">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-black text-white mb-6">Jangan Tunda Kesehatan Anda</h2>
            <p class="text-slate-400 text-lg mb-8 max-w-2xl mx-auto">Daftar antrian secara online dari rumah, datang sesuai jadwal, dan nikmati pelayanan bebas ribet.</p>
            <a href="/antrian-online" wire:navigate class="inline-block bg-emerald-500 hover:bg-emerald-400 text-slate-900 px-10 py-4 rounded-xl font-black text-lg transition shadow-xl shadow-emerald-500/20">
                Daftar Sekarang
            </a>
        </div>
    </div>
</div>
