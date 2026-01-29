<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-3xl font-bold text-gray-900">Fasilitas Lengkap & Modern</h1>
            <p class="text-gray-500 mt-2 max-w-2xl mx-auto">Kami menyediakan fasilitas kesehatan terbaik untuk menunjang kenyamanan dan kesembuhan pasien dengan standar Integrasi Layanan Primer.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            @forelse($fasilitas as $item)
            <div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition">
                <div class="md:w-1/3 bg-gray-200 min-h-[200px] flex items-center justify-center text-6xl text-gray-400">
                    🏥
                </div>
                <div class="p-8 md:w-2/3 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $item->nama_fasilitas }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $item->deskripsi }}
                    </p>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-12">
                <p class="text-gray-400">Belum ada data fasilitas.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
