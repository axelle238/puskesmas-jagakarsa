<div>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Selamat Datang, {{ auth()->user()->nama_lengkap }} 👋</h1>
        <p class="text-slate-500">Pusat Komando Operasional Puskesmas Jagakarsa (Executive Dashboard).</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Pasien (MEDIS) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pasien Hari Ini</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalPasienHariIni }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Pelayanan Medis
                </p>
            </div>
            <div class="bg-blue-50 text-blue-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Kehadiran Pegawai (SDM) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pegawai Hadir</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $pegawaiHadir }}</h3>
                <p class="text-xs text-purple-600 font-medium mt-2">SDM & Kepegawaian</p>
            </div>
            <div class="bg-purple-50 text-purple-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Pendapatan (KEUANGAN) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Omset Tunai</p>
                <h3 class="text-3xl font-black text-slate-800">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-2">Keuangan & Kasir</p>
            </div>
            <div class="bg-emerald-50 text-emerald-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Stok Kritis (FARMASI) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Stok Menipis</p>
                <h3 class="text-3xl font-black {{ $obatMenipis->count() > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $obatMenipis->count() }}</h3>
                <p class="text-xs text-red-500 font-medium mt-2">Logistik Farmasi</p>
            </div>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Grafik & Tabel -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Chart Kunjungan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-900 mb-4">Tren Kunjungan (7 Hari Terakhir)</h2>
                <div class="h-64">
                    <canvas id="kunjunganChart"></canvas>
                </div>
            </div>

            <!-- Tabel Antrian -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="font-bold text-slate-900">Antrian Terbaru</h2>
                    <a href="{{ route('medis.antrian') }}" wire:navigate class="text-sm text-emerald-600 font-medium hover:text-emerald-700">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-6 py-3 font-medium">No. Antrian</th>
                                <th class="px-6 py-3 font-medium">Pasien</th>
                                <th class="px-6 py-3 font-medium">Poli Tujuan</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($antrianTerbaru as $a)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $a->nomor_antrian }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $a->pasien->nama_lengkap }}</div>
                                    <div class="text-xs text-slate-400">{{ $a->pasien->no_rekam_medis }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-medium">{{ $a->poli->nama_poli }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada antrian.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Stok Kritis -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden h-fit">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-bold text-slate-900">Peringatan Stok Obat</h2>
                <a href="{{ route('farmasi.stok') }}" wire:navigate class="text-sm text-emerald-600 font-medium hover:text-emerald-700">Kelola</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($obatMenipis as $obat)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div>
                        <p class="font-medium text-slate-900">{{ $obat->nama_obat }}</p>
                        <p class="text-xs text-slate-500">{{ $obat->kategori }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600">{{ $obat->stok_saat_ini }} <span class="text-xs font-normal text-slate-400">{{ $obat->satuan }}</span></p>
                        <p class="text-xs text-slate-400">Min: {{ $obat->stok_minimum }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-sm">
                    <svg class="w-8 h-8 mx-auto mb-2 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Semua stok aman.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('kunjunganChart');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($grafikLabel),
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: @json($grafikData),
                    borderColor: '#059669', // Emerald 600
                    backgroundColor: '#10b98120', // Emerald 500 transparent
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</div>
