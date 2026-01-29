<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel - Puskesmas Jagakarsa' }}</title>
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        utama: '#0f172a', // Slate 900
                        aksen: '#3b82f6', // Blue 500
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    <aside class="bg-utama text-white flex flex-col transition-all duration-300 ease-in-out" 
           :class="sidebarOpen ? 'w-64' : 'w-20'">
        
        <!-- Logo -->
        <div class="h-16 flex items-center justify-center border-b border-gray-800" :class="sidebarOpen ? 'px-6' : 'px-0'">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-aksen rounded flex items-center justify-center font-bold text-xl">P</div>
                <span class="font-bold text-lg tracking-wider" x-show="sidebarOpen" x-transition>ADMIN</span>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <x-nav-link href="/dasbor" icon="🏠" :active="request()->is('dasbor')">Dasbor</x-nav-link>
            
            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase" x-show="sidebarOpen">Layanan Medis</div>
            <x-nav-link href="/pemeriksaan" icon="🩺" :active="request()->is('pemeriksaan*')">Pemeriksaan</x-nav-link>
            <x-nav-link href="/farmasi/resep" icon="💊" :active="request()->is('farmasi/resep*')">Resep Masuk</x-nav-link>
            <x-nav-link href="/pasien" icon="👥" :active="request()->is('pasien*')">Data Pasien</x-nav-link>
            
            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase" x-show="sidebarOpen">Manajemen</div>
            <x-nav-link href="/farmasi/stok" icon="📦" :active="request()->is('farmasi/stok*')">Stok Obat</x-nav-link>
            <x-nav-link href="/pegawai" icon="id-card" :active="request()->is('pegawai*')">Data Pegawai</x-nav-link>
            <x-nav-link href="/publikasi/artikel" icon="📰" :active="request()->is('publikasi/artikel*')">Artikel Edukasi</x-nav-link>
            <x-nav-link href="/publikasi/fasilitas" icon="🏥" :active="request()->is('publikasi/fasilitas*')">Data Fasilitas</x-nav-link>
            
            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase" x-show="sidebarOpen">Laporan</div>
            <x-nav-link href="/laporan/kunjungan" icon="📊" :active="request()->is('laporan/kunjungan*')">Kunjungan</x-nav-link>
            <x-nav-link href="/laporan/penyakit" icon="🦠" :active="request()->is('laporan/penyakit*')">10 Besar Penyakit</x-nav-link>
            
            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase" x-show="sidebarOpen">Pengaturan</div>
            <x-nav-link href="/poli" icon="⚙️" :active="request()->is('poli*')">Poli / Unit</x-nav-link>
            <x-nav-link href="/jadwal" icon="📅" :active="request()->is('jadwal*')">Jadwal Praktik</x-nav-link>
        </nav>

        <!-- User Profile Bottom -->
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3">
                <a href="/profil" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-gray-600 transition">
                    👤
                </a>
                <div class="flex-1 min-w-0" x-show="sidebarOpen">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->nama_lengkap ?? 'Admin' }}</p>
                    <a href="/profil" class="text-xs text-gray-400 truncate hover:text-white">Pengaturan Akun</a>
                </div>
            </div>
            <a href="/login" class="block mt-4 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm font-medium transition text-center" x-show="sidebarOpen">
                Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
            </button>
            
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                <div class="relative">
                    <span class="w-2 h-2 bg-red-500 rounded-full absolute top-0 right-0"></span>
                    <span class="text-xl">🔔</span>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto p-6 bg-gray-100" id="main-content">
            {{ $slot }}
        </main>
    </div>

</body>
</html>