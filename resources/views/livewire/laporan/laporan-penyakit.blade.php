<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Morbiditas</h1>
            <p class="text-gray-500">10 Besar Penyakit Terbanyak (Top 10 Diseases)</p>
        </div>
        
        <!-- Filter -->
        <div class="flex gap-2 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
            <select wire:model.live="bulan" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option>
                @endforeach
            </select>
            <select wire:model.live="tahun" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach(range(Carbon::now()->year, Carbon::now()->year - 5) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            <button onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md transition" title="Cetak">
                🖨️
            </button>
        </div>
    </div>

    <!-- Chart Visualisasi (Opsional) -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 text-center">Grafik Distribusi Penyakit - {{ Carbon\Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}</h3>
        <div class="relative h-64 w-full">
            <canvas id="diseaseChart"></canvas>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-blue-50 text-blue-700 font-bold border-b border-blue-100 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 w-16 text-center">Peringkat</th>
                    <th class="px-6 py-4">Kode ICD-10</th>
                    <th class="px-6 py-4">Diagnosa Penyakit</th>
                    <th class="px-6 py-4 text-right">Jumlah Kasus</th>
                    <th class="px-6 py-4 w-32">Persentase</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php $totalKasus = $penyakits->sum('total'); @endphp
                @forelse($penyakits as $index => $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center font-bold text-gray-500">
                        @if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else {{ $index + 1 }} @endif
                    </td>
                    <td class="px-6 py-4 font-mono font-bold text-blue-600">{{ $p->diagnosis_kode }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $p->asesmen }}</td>
                    <td class="px-6 py-4 text-right font-bold">{{ $p->total }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $totalKasus > 0 ? ($p->total / $totalKasus) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-8">{{ $totalKasus > 0 ? round(($p->total / $totalKasus) * 100) : 0 }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        Tidak ada data penyakit tercatat pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            renderChart();
        });

        // Re-render chart saat Livewire update (filter berubah)
        document.addEventListener('livewire:updated', () => {
            renderChart();
        });

        let chartInstance = null;

        function renderChart() {
            const ctx = document.getElementById('diseaseChart');
            if (!ctx) return;

            // Hancurkan chart lama jika ada
            if (chartInstance) {
                chartInstance.destroy();
            }

            const labels = @json($penyakits->pluck('diagnosis_kode'));
            const data = @json($penyakits->pluck('total'));
            const descriptions = @json($penyakits->pluck('asesmen'));

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Kasus',
                        data: data,
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    return descriptions[tooltipItems[0].dataIndex];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #main-content, #main-content * {
                visibility: visible;
            }
            #main-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            /* Sembunyikan elemen non-cetak */
            header, aside, button {
                display: none !important;
            }
        }
    </style>
</div>
