<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Beranda' }} - Puskesmas Jagakarsa</title>
    <meta name="description" content="Sistem Informasi Pelayanan Kesehatan Masyarakat Puskesmas Jagakarsa">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <img class="h-10 w-auto" src="{{ asset('logo-puskesmas.svg') }}" alt="Logo">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 leading-none">PUSKESMAS</h1>
                        <p class="text-sm text-emerald-600 font-bold tracking-wider">JAGAKARSA</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/" wire:navigate class="text-slate-600 hover:text-emerald-600 font-bold text-sm uppercase transition">Beranda</a>
                    <a href="/#layanan" class="text-slate-600 hover:text-emerald-600 font-bold text-sm uppercase transition">Layanan</a>
                    <a href="/#jadwal" class="text-slate-600 hover:text-emerald-600 font-bold text-sm uppercase transition">Jadwal</a>
                    <a href="/#artikel" class="text-slate-600 hover:text-emerald-600 font-bold text-sm uppercase transition">Artikel</a>
                    <a href="/antrian-online" wire:navigate class="text-slate-600 hover:text-emerald-600 font-bold text-sm uppercase transition">Antrian Online</a>
                </nav>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center">
                    <a href="/login" wire:navigate class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-lg shadow-slate-900/20">
                        Login Pegawai
                    </a>
                </div>

                <!-- Mobile Menu Button (Placeholder) -->
                <div class="flex items-center md:hidden">
                    <button type="button" class="text-slate-500 hover:text-slate-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <img class="h-8 w-auto brightness-0 invert" src="{{ asset('logo-puskesmas.svg') }}" alt="Logo">
                        <div>
                            <h2 class="text-lg font-bold leading-none">PUSKESMAS</h2>
                            <p class="text-xs text-emerald-400 font-bold tracking-wider">JAGAKARSA</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Memberikan pelayanan kesehatan terbaik yang profesional, merata, dan terjangkau bagi seluruh lapisan masyarakat.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-lg font-bold mb-6 text-emerald-400">Tautan Cepat</h3>
                    <ul class="space-y-3 text-sm text-slate-300">
                        <li><a href="#" class="hover:text-white transition">Profil Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Layanan Medis</a></li>
                        <li><a href="#" class="hover:text-white transition">Dokter Spesialis</a></li>
                        <li><a href="#" class="hover:text-white transition">Jadwal Praktik</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h3 class="text-lg font-bold mb-6 text-emerald-400">Hubungi Kami</h3>
                    <ul class="space-y-3 text-sm text-slate-300">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Moh. Kahfi 1 No.17, Jagakarsa, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>(021) 786-xxxx</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>info@puskesmas-jagakarsa.go.id</span>
                        </li>
                    </ul>
                </div>

                <!-- Jam Buka -->
                <div>
                    <h3 class="text-lg font-bold mb-6 text-emerald-400">Jam Operasional</h3>
                    <ul class="space-y-3 text-sm text-slate-300">
                        <li class="flex justify-between">
                            <span>Senin - Jumat</span>
                            <span class="font-bold text-white">07:30 - 16:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sabtu</span>
                            <span class="font-bold text-white">08:00 - 12:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Minggu / Libur</span>
                            <span class="font-bold text-red-400">Tutup</span>
                        </li>
                        <li class="mt-4 pt-4 border-t border-slate-700 text-xs text-slate-400">
                            IGD & Persalinan buka 24 Jam
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-slate-500 text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} Puskesmas Kecamatan Jagakarsa. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-slate-500 hover:text-white transition"><span class="sr-only">Facebook</span>FB</a>
                    <a href="#" class="text-slate-500 hover:text-white transition"><span class="sr-only">Instagram</span>IG</a>
                    <a href="#" class="text-slate-500 hover:text-white transition"><span class="sr-only">Twitter</span>TW</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
