<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl font-bold text-slate-900 mb-4">Fasilitas & Sarana</h1>
            <p class="text-lg text-slate-500">Kami menyediakan fasilitas kesehatan modern dan lengkap untuk menunjang kenyamanan serta kesembuhan pasien.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($fasilitas as $f)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="h-64 overflow-hidden relative">
                    @if($f->foto)
                        <img src="{{ asset('storage/' . $f->foto) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <h3 class="text-xl font-bold">{{ $f->nama_fasilitas }}</h3>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-slate-600 leading-relaxed">{{ $f->deskripsi }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20">
                <p class="text-slate-400">Belum ada data fasilitas.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>