<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Puskesmas Jagakarsa' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">
    
    <!-- Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-emerald-100 shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="/" wire:navigate class="flex items-center gap-3">
                        <div class="bg-emerald-600 text-white p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-emerald-900 leading-none">Puskesmas Jagakarsa</h1>
                            <p class="text-xs text-emerald-600 font-medium">Layanan Kesehatan Terpadu</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Beranda</a>
                    <a href="{{ route('publik.artikel') }}" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Edukasi</a>
                    <a href="{{ route('publik.fasilitas') }}" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Fasilitas</a>
                    <a href="{{ route('publik.jadwal') }}" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Jadwal</a>
                </nav>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('publik.ambil-antrian') }}" wire:navigate class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-full font-semibold text-sm transition-all shadow-lg shadow-emerald-200">
                        Ambil Antrian
                    </a>
                    @auth
                        <a href="{{ route('dasbor') }}" wire:navigate class="text-emerald-700 hover:bg-emerald-50 px-4 py-2.5 rounded-full font-semibold text-sm transition-colors">
                            Dasbor
                        </a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="text-emerald-700 hover:bg-emerald-50 px-4 py-2.5 rounded-full font-semibold text-sm transition-colors">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <h3 class="text-lg font-bold mb-4">Puskesmas Jagakarsa</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Melayani dengan hati, mengutamakan kesehatan masyarakat Jagakarsa dengan standar pelayanan prima.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400">Poli Umum</a></li>
                        <li><a href="#" class="hover:text-emerald-400">Poli Gigi</a></li>
                        <li><a href="#" class="hover:text-emerald-400">KIA</a></li>
                        <li><a href="#" class="hover:text-emerald-400">Laboratorium</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('publik.artikel') }}" class="hover:text-emerald-400">Artikel Kesehatan</a></li>
                        <li><a href="{{ route('publik.fasilitas') }}" class="hover:text-emerald-400">Fasilitas</a></li>
                        <li><a href="{{ route('publik.jadwal') }}" class="hover:text-emerald-400">Jadwal Dokter</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li>Jl. Moh. Kahfi 1, Jagakarsa</li>
                        <li>(021) 786-xxxx</li>
                        <li>info@puskesmas-jagakarsa.go.id</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} Puskesmas Jagakarsa. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>