<div class="flex flex-col gap-16 pb-16">
    
    <!-- Hero Section -->
    <section class="relative bg-emerald-900 py-20 lg:py-32 overflow-hidden">
        <!-- Background Image CMS -->
        @if($profil && $profil->hero_image)
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $profil->hero_image) }}" class="w-full h-full object-cover opacity-20">
            </div>
        @else
            <div class="absolute inset-0 opacity-10 pattern-dots bg-emerald-50"></div>
        @endif
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <span class="inline-block py-1 px-3 rounded-full bg-emerald-800 text-emerald-200 text-sm font-semibold mb-6 border border-emerald-700">
                    {{ $profil->nama_instansi ?? 'Selamat Datang di Puskesmas' }}
                </span>
                <h1 class="text-4xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    {{ $profil->hero_title ?? 'Layanan Kesehatan Modern & Terintegrasi Untuk Keluarga Anda.' }}
                </h1>
                <p class="text-lg text-emerald-100 mb-8 leading-relaxed max-w-2xl">
                    {{ $profil->hero_subtitle ?? 'Kami hadir dengan standar Integrasi Layanan Primer (ILP) untuk memberikan pelayanan kesehatan yang lebih komprehensif.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/ambil-antrian" wire:navigate class="inline-flex justify-center items-center bg-white text-emerald-900 hover:bg-emerald-50 px-8 py-4 rounded-xl font-bold text-lg transition-all shadow-xl">
                        Ambil Antrian Online
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="/jadwal-dokter" wire:navigate class="inline-flex justify-center items-center bg-emerald-800/50 hover:bg-emerald-800 text-white border border-emerald-700 px-8 py-4 rounded-xl font-semibold text-lg transition-all backdrop-blur-sm">
                        Lihat Jadwal Dokter
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sambutan Kepala Puskesmas (CMS) -->
    @if($profil && $profil->sambutan_kepala)
    <section class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-8 md:gap-12">
            <div class="md:w-1/3 flex justify-center">
                <div class="relative w-48 h-48 md:w-64 md:h-64">
                    @if($profil->foto_kepala)
                        <img src="{{ asset('storage/' . $profil->foto_kepala) }}" class="w-full h-full object-cover rounded-full border-4 border-emerald-100 shadow-lg">
                    @else
                        <div class="w-full h-full bg-slate-200 rounded-full flex items-center justify-center text-slate-400">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    @endif
                    <div class="absolute bottom-2 right-2 bg-emerald-600 text-white p-2 rounded-full shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="md:w-2/3 text-center md:text-left">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Sambutan Kepala Puskesmas</h2>
                <p class="text-slate-600 leading-relaxed italic mb-6">
                    "{{ $profil->sambutan_kepala }}"
                </p>
                <div>
                    <p class="font-bold text-slate-900 text-lg">{{ $profil->nama_kepala_puskesmas }}</p>
                    <p class="text-emerald-600 text-sm font-medium">Kepala Puskesmas</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Info Bar / Statistik -->
    <section class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl p-8 grid grid-cols-1 md:grid-cols-3 gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100">
            <div class="text-center md:text-left">
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider mb-1">Total Kunjungan Hari Ini</p>
                <p class="text-4xl font-bold text-slate-900">{{ $totalAntrian }} <span class="text-lg text-slate-400 font-normal">Pasien</span></p>
            </div>
            <div class="text-center md:text-left md:pl-8">
                <p class="text-emerald-600 text-sm font-medium uppercase tracking-wider mb-1">Sedang Dilayani</p>
                <p class="text-4xl font-bold text-emerald-600">{{ $sedangDilayani }} <span class="text-lg text-emerald-400 font-normal">Pasien</span></p>
            </div>
            <div class="text-center md:text-left md:pl-8">
                <p class="text-orange-500 text-sm font-medium uppercase tracking-wider mb-1">Menunggu Antrian</p>
                <p class="text-4xl font-bold text-orange-500">{{ $sisaAntrian }} <span class="text-lg text-orange-300 font-normal">Pasien</span></p>
            </div>
        </div>
    </section>

    <!-- Layanan ILP Grid -->
    <section class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Integrasi Layanan Primer</h2>
            <p class="text-slate-500">Pelayanan kesehatan kini dikelompokkan berdasarkan siklus hidup untuk penanganan yang lebih fokus dan menyeluruh.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($klaster as $k)
            <div class="bg-white rounded-xl p-6 border border-slate-100 hover:shadow-lg hover:border-emerald-100 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $k->nama_klaster }}</h3>
                <p class="text-slate-500 text-sm mb-4">{{ $k->deskripsi_layanan }}</p>
                @if($k->poli->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($k->poli as $poli)
                            <span class="inline-block text-xs font-medium px-2 py-1 bg-slate-100 text-slate-600 rounded">{{ $poli->nama_poli }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            @empty
            <div class="col-span-4 text-center py-12 text-slate-400">
                Belum ada data klaster layanan.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Artikel Edukasi -->
    <section class="bg-slate-50 py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Edukasi Kesehatan</h2>
                    <p class="text-slate-500">Artikel dan informasi kesehatan terbaru untuk Anda.</p>
                </div>
                <a href="/artikel" class="hidden md:inline-flex items-center font-semibold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($artikel as $a)
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="aspect-video bg-slate-200">
                        @if($a->gambar_sampul)
                            <img src="{{ asset('storage/' . $a->gambar_sampul) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold mb-3">{{ $a->kategori }}</span>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2">
                            <a href="#" class="hover:text-emerald-600 transition-colors">{{ $a->judul }}</a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4">{{ $a->ringkasan }}</p>
                        <div class="text-xs text-slate-400">
                            {{ $a->created_at->isoFormat('D MMMM Y') }}
                        </div>
                    </div>
                </article>
                @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300 text-slate-400">
                    Belum ada artikel terbaru.
                </div>
                @endforelse
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="/artikel" class="inline-flex items-center font-semibold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua Artikel
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

</div>