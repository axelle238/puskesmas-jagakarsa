<div class="bg-white min-h-screen pb-12">
    <!-- Hero / Cover -->
    <div class="w-full h-[400px] bg-slate-900 relative">
        @if($artikel->gambar_sampul)
            <img src="{{ asset('storage/' . $artikel->gambar_sampul) }}" class="w-full h-full object-cover opacity-60">
        @else
            <div class="w-full h-full bg-emerald-900 opacity-80 pattern-dots"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-8 md:p-12">
            <div class="container mx-auto max-w-4xl">
                <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 inline-block">{{ $artikel->kategori }}</span>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">{{ $artikel->judul }}</h1>
                <div class="flex items-center gap-4 text-slate-300 text-sm">
                    <span>Oleh: {{ $artikel->penulis->nama_lengkap ?? 'Tim Medis' }}</span>
                    <span>•</span>
                    <span>{{ $artikel->created_at->isoFormat('D MMMM Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Main Content -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-xl shadow-xl p-8 md:p-12">
                    <p class="text-lg text-slate-600 font-medium mb-8 leading-relaxed border-l-4 border-emerald-500 pl-4 italic">
                        {{ $artikel->ringkasan }}
                    </p>
                    
                    <div class="prose prose-emerald prose-lg max-w-none text-slate-800">
                        {!! nl2br(e($artikel->konten)) !!}
                    </div>

                    <div class="mt-12 pt-8 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Bagikan Artikel:</h3>
                        <div class="flex gap-2">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Facebook</button>
                            <button class="bg-sky-500 text-white px-4 py-2 rounded-lg text-sm font-bold">Twitter</button>
                            <button class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-bold">WhatsApp</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/3 space-y-8 mt-12 lg:mt-0">
                <!-- Terkait -->
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-6 border-l-4 border-emerald-500 pl-3">Artikel Terkait</h3>
                    <div class="space-y-6">
                        @forelse($terkait as $t)
                        <div class="flex gap-4 group cursor-pointer">
                            <div class="w-24 h-24 bg-slate-200 rounded-lg overflow-hidden flex-shrink-0">
                                @if($t->gambar_sampul)
                                    <img src="{{ asset('storage/' . $t->gambar_sampul) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 leading-snug mb-1 group-hover:text-emerald-600 transition-colors">
                                    <a href="{{ route('publik.artikel.baca', $t->slug) }}" wire:navigate>{{ $t->judul }}</a>
                                </h4>
                                <span class="text-xs text-slate-500">{{ $t->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-slate-500 text-sm">Tidak ada artikel terkait.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Banner Antrian -->
                <div class="bg-emerald-600 rounded-2xl p-8 text-center text-white">
                    <h3 class="text-2xl font-bold mb-2">Butuh Layanan Medis?</h3>
                    <p class="mb-6 opacity-90">Daftar antrian online sekarang untuk pelayanan yang lebih cepat.</p>
                    <a href="{{ route('publik.ambil-antrian') }}" wire:navigate class="inline-block bg-white text-emerald-700 font-bold px-6 py-3 rounded-xl shadow-lg hover:bg-emerald-50 transition-colors w-full">
                        Ambil Antrian
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>