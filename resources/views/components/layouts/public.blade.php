<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Puskesmas Jagakarsa' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medis: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9', // Sky Blue
                            600: '#0284c7',
                            900: '#0c4a6e',
                        },
                        sehat: {
                            500: '#10b981', // Emerald
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 flex flex-col min-h-screen">

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-medis-500 rounded-lg flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        +
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-xl text-medis-900 leading-none">PUSKESMAS</span>
                        <span class="text-sm text-gray-500 font-medium tracking-wide">JAGAKARSA</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8 items-center">
                    <a href="/" wire:navigate class="text-gray-600 hover:text-medis-600 font-medium transition">Beranda</a>
                    <a href="/layanan" wire:navigate class="text-gray-600 hover:text-medis-600 font-medium transition">Layanan & Poli</a>
                    <a href="/jadwal" wire:navigate class="text-gray-600 hover:text-medis-600 font-medium transition">Jadwal Dokter</a>
                    <a href="/antrian" wire:navigate class="px-4 py-2 bg-medis-50 text-medis-600 rounded-full font-medium hover:bg-medis-100 transition">Ambil Antrian</a>
                    @auth
                        <a href="/dasbor" wire:navigate class="px-5 py-2.5 bg-medis-600 text-white rounded-lg font-bold shadow-md hover:bg-medis-700 transition transform hover:-translate-y-0.5">
                            Masuk Dasbor
                        </a>
                    @else
                        <a href="/login" wire:navigate class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-bold shadow-md hover:bg-gray-800 transition transform hover:-translate-y-0.5">
                            Login Pegawai
                        </a>
                    @endauth
                </nav>

                <!-- Mobile Menu Button (Placeholder) -->
                <div class="flex items-center md:hidden">
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-medis-500 rounded flex items-center justify-center text-sm">+</span>
                        Puskesmas Jagakarsa
                    </h3>
                    <p class="text-gray-400 leading-relaxed mb-6 max-w-md">
                        Melayani dengan hati, mengutamakan kesehatan masyarakat Jagakarsa dengan standar pelayanan Integrasi Layanan Primer (ILP) yang modern dan terpercaya.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Icons Placeholder -->
                        <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:bg-medis-500 hover:text-white transition cursor-pointer">IG</div>
                        <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:bg-medis-500 hover:text-white transition cursor-pointer">FB</div>
                        <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:bg-medis-500 hover:text-white transition cursor-pointer">YT</div>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6 border-b border-gray-700 pb-2 inline-block">Layanan Kami</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#" class="hover:text-medis-400 transition">Poli Umum</a></li>
                        <li><a href="#" class="hover:text-medis-400 transition">Poli Gigi & Mulut</a></li>
                        <li><a href="#" class="hover:text-medis-400 transition">KIA & KB</a></li>
                        <li><a href="#" class="hover:text-medis-400 transition">Laboratorium</a></li>
                        <li><a href="#" class="hover:text-medis-400 transition">IGD 24 Jam</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6 border-b border-gray-700 pb-2 inline-block">Kontak</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start gap-3">
                            <span class="mt-1">📍</span>
                            <span>Jl. Moh. Kahfi 1, Jagakarsa, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span>📞</span>
                            <span>(021) 786-xxxx</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span>📧</span>
                            <span>info@pkc-jagakarsa.go.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
                &copy; 2026 Puskesmas Kecamatan Jagakarsa. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
