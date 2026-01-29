<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-4">Edukasi Kesehatan</h1>
            <p class="text-slate-500">Temukan informasi kesehatan terpercaya dari tenaga medis kami.</p>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="flex gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                <button wire:click="$set('kategori', '')" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $kategori == '' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua</button>
                <button wire:click="$set('kategori', 'Umum')" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $kategori == 'Umum' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Umum</button>
                <button wire:click="$set('kategori', 'Ibu & Anak')" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $kategori == 'Ibu & Anak' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Ibu & Anak</button>
                <button wire:click="$set('kategori', 'Penyakit Menular')" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $kategori == 'Penyakit Menular' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Penyakit Menular</button>
            </div>
            <div class="w-full md:w-64">
                <input wire:model.live.debounce.300ms="cari" type="text" class="w-full rounded-full border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Cari artikel...">
            </div>
        </div>

        <!-- Grid Artikel -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($artikel as $a)
            <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow border border-slate-100 flex flex-col h-full">
                <div class="aspect-video bg-slate-200 relative overflow-hidden group">
                    @if($a->gambar_sampul)
                        <img src="{{ asset('storage/' . $a->gambar_sampul) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur text-emerald-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm">{{ $a->kategori }}</span>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="text-xs text-slate-400 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $a->created_at->isoFormat('D MMMM Y') }}
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 hover:text-emerald-600 transition-colors">
                        <a href="{{ route('publik.artikel.baca', $a->slug) }}" wire:navigate>{{ $a->judul }}</a>
                    </h3>
                    <p class="text-slate-500 text-sm line-clamp-3 mb-6 flex-1">{{ $a->ringkasan }}</p>
                    <a href="{{ route('publik.artikel.baca', $a->slug) }}" wire:navigate class="text-emerald-600 font-bold text-sm hover:underline flex items-center gap-1">
                        Baca Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-20 bg-white rounded-2xl border border-dashed border-slate-300">
                <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <h3 class="text-lg font-medium text-slate-900">Belum ada artikel ditemukan</h3>
                <p class="text-slate-500">Coba kata kunci lain atau pilih kategori berbeda.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $artikel->links() }}
        </div>
    </div>
</div>