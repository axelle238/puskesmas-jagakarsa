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
                    <div class="bg-emerald-600 text-white p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-emerald-900 leading-none">Puskesmas Jagakarsa</h1>
                        <p class="text-xs text-emerald-600 font-medium">Layanan Kesehatan Terpadu</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Beranda</a>
                    <a href="/layanan" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Layanan</a>
                    <a href="/jadwal-dokter" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Jadwal Dokter</a>
                    <a href="/artikel" wire:navigate class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Edukasi</a>
                </nav>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="/ambil-antrian" wire:navigate class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-full font-semibold text-sm transition-all shadow-lg shadow-emerald-200">
                        Ambil Antrian
                    </a>
                    <a href="/masuk" wire:navigate class="text-emerald-700 hover:bg-emerald-50 px-4 py-2.5 rounded-full font-semibold text-sm transition-colors">
                        Masuk
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="text-slate-500 hover:text-emerald-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- About -->
                <div>
                    <div class="flex items-center gap-2 mb-6">
                        <div class="bg-emerald-500 text-white p-1.5 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold">Puskesmas Jagakarsa</h3>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Memberikan pelayanan kesehatan primer yang berkualitas, merata, dan terjangkau bagi seluruh masyarakat Jagakarsa dengan pendekatan Integrasi Layanan Primer (ILP).
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 border-b border-slate-700 pb-2 inline-block">Tautan Cepat</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Jadwal Dokter</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Cek Antrian</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Pengaduan Masyarakat</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 border-b border-slate-700 pb-2 inline-block">Layanan Kami</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Poli Umum</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Poli Gigi & Mulut</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">KIA & KB</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Laboratorium</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Farmasi</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 border-b border-slate-700 pb-2 inline-block">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Jl. Moh. Kahfi 1, Jagakarsa, Jakarta Selatan, DKI Jakarta 12620</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>(021) 786-xxxx</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>info@puskesmas-jagakarsa.go.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Puskesmas Jagakarsa. Hak Cipta Dilindungi.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
