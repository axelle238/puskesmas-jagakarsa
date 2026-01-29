<div class="py-12 bg-white min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ route('edukasi') }}" wire:navigate class="text-blue-600 font-medium hover:underline flex items-center gap-1">
                &larr; Kembali ke Daftar Artikel
            </a>
        </div>

        <article class="prose prose-blue lg:prose-lg mx-auto">
            <span class="bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded font-bold uppercase tracking-wide">
                {{ $artikel->kategori }}
            </span>
            
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-4 mb-4 leading-tight">
                {{ $artikel->judul }}
            </h1>

            <div class="flex items-center gap-4 text-sm text-gray-500 mb-8 pb-8 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                        {{ substr($artikel->penulis->nama_lengkap, 0, 1) }}
                    </div>
                    <span>{{ $artikel->penulis->nama_lengkap }}</span>
                </div>
                <span>•</span>
                <time>{{ $artikel->created_at->format('d F Y') }}</time>
            </div>

            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $artikel->konten }}
            </div>
        </article>

    </div>
</div>
