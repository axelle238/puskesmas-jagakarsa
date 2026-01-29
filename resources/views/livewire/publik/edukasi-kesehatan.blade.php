<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">Edukasi & Informasi Kesehatan</h1>
            <p class="text-gray-500 mt-2">Artikel terbaru seputar kesehatan dari tim medis Puskesmas Jagakarsa</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($artikels as $artikel)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full">
                <!-- Placeholder Image -->
                <div class="h-48 bg-gradient-to-br from-blue-100 to-green-100 flex items-center justify-center text-4xl">
                    📰
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="mb-3">
                        <span class="bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded font-bold uppercase tracking-wide">
                            {{ $artikel->kategori }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                        <a href="{{ route('artikel.baca', $artikel->slug) }}" wire:navigate class="hover:text-blue-600 transition">
                            {{ $artikel->judul }}
                        </a>
                    </h3>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-3 flex-1">
                        {{ $artikel->ringkasan }}
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-400 mt-auto pt-4 border-t border-gray-50">
                        <span>Oleh: {{ $artikel->penulis->nama_lengkap }}</span>
                        <span>{{ $artikel->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-400 text-lg">Belum ada artikel yang diterbitkan.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $artikels->links() }}
        </div>
    </div>
</div>
