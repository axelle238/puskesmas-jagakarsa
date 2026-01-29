<div>
    <!-- Header Page -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ringkasan Operasional</h1>
            <p class="text-gray-500">Pantau aktivitas Puskesmas secara real-time</p>
        </div>
        <div>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-400">
                Live Update
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Antrian Hari Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAntrianHariIni }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-500">
                    👥
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <span class="{{ $antrianMenunggu > 0 ? 'text-orange-500 font-bold' : 'text-green-500' }}">
                    {{ $antrianMenunggu }}
                </span> menunggu dipanggil
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pasien</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalPasien) }}</h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-500">
                    🗂️
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Terdaftar dalam database
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dokter Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $dokterAktif }}</h3>
                </div>
                <div class="p-2 bg-purple-50 rounded-lg text-purple-500">
                    👨‍⚕️
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Siap melayani hari ini
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Layanan ILP</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">4</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                    🔄
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Klaster Layanan Primer
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Chart Section -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="font-bold text-gray-800 mb-4">Tren Kunjungan (7 Hari Terakhir)</h3>
            <div class="relative h-64 w-full">
                <canvas id="visitChart"></canvas>
            </div>
        </div>

        <!-- Mini List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm">Antrian Masuk Terkini</h3>
                <a href="#" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($antrianTerbaru as $antrian)
                <div class="p-4 hover:bg-gray-50 transition flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                            {{ $antrian->nomor_antrian }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm">{{ $antrian->pasien->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $antrian->poli->nama_poli }}</div>
                        </div>
                    </div>
                    <div>
                        @if($antrian->status == 'menunggu')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">Menunggu</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">{{ ucfirst($antrian->status) }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 text-sm">
                    Belum ada antrian hari ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('visitChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Jumlah Pasien',
                            data: @json($chartData),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
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
                                grid: {
                                    borderDash: [2, 4]
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</div>
