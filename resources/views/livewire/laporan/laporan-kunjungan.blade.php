<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Laporan Kunjungan Pasien</h1>
        <p class="text-slate-500">Rekapitulasi statistik kunjungan per periode.</p>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Dari Tanggal</label>
            <input type="date" wire:model.live="tanggal_mulai" class="rounded-lg border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Sampai Tanggal</label>
            <input type="date" wire:model.live="tanggal_selesai" class="rounded-lg border-slate-300 text-sm">
        </div>
        <div class="ml-auto">
            <button onclick="window.print()" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-emerald-600 text-white p-6 rounded-xl shadow-lg shadow-emerald-200">
            <h3 class="text-lg font-medium opacity-80">Total Kunjungan</h3>
            <p class="text-4xl font-black mt-2">{{ $totalKunjungan }}</p>
            <p class="text-sm mt-2 opacity-80">Periode terpilih</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 col-span-2">
            <h3 class="font-bold text-slate-800 mb-4">Tren Kunjungan Harian</h3>
            <div class="h-32">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tabel Rekap Poli -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-6">
            <div class="p-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Kunjungan per Poli / Layanan</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Poli</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($laporanPoli as $lp)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $lp->nama_poli }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-slate-900">{{ $lp->jumlah_kunjungan }}</td>
                        <td class="px-6 py-4 text-right text-sm text-slate-600">
                            {{ $totalKunjungan > 0 ? round(($lp->jumlah_kunjungan / $totalKunjungan) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Chart Poli -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
            <h3 class="font-bold text-slate-800 mb-4">Distribusi Poli</h3>
            <div class="h-64">
                <canvas id="poliChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Harian -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Detail Kunjungan Harian</h3>
        </div>
        <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 sticky top-0">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Jumlah Pasien</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($rekapHarian as $rh)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-900">
                            {{ \Carbon\Carbon::parse($rh->tanggal)->isoFormat('dddd, D MMMM Y') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-slate-900">{{ $rh->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let dailyChartInstance = null;
        let poliChartInstance = null;

        document.addEventListener('livewire:navigated', () => {
            renderCharts();
        });

        // Re-render when Livewire updates (e.g. date filter change)
        document.addEventListener('livewire:updated', () => {
            renderCharts();
        });

        function renderCharts() {
            // Data Setup
            const poliLabels = @json($laporanPoli->pluck('nama_poli'));
            const poliData = @json($laporanPoli->pluck('jumlah_kunjungan'));
            
            const dailyLabels = @json($rekapHarian->map(fn($r) => \Carbon\Carbon::parse($r->tanggal)->format('d/m')));
            const dailyData = @json($rekapHarian->pluck('total'));

            // Destroy existing charts to prevent duplication
            if (dailyChartInstance) dailyChartInstance.destroy();
            if (poliChartInstance) poliChartInstance.destroy();

            // Daily Line Chart
            const ctxDaily = document.getElementById('dailyChart');
            if (ctxDaily) {
                dailyChartInstance = new Chart(ctxDaily, {
                    type: 'line',
                    data: {
                        labels: dailyLabels,
                        datasets: [{
                            label: 'Kunjungan',
                            data: dailyData,
                            borderColor: '#059669',
                            backgroundColor: '#10b98120',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                    }
                });
            }

            // Poli Pie/Doughnut Chart
            const ctxPoli = document.getElementById('poliChart');
            if (ctxPoli) {
                poliChartInstance = new Chart(ctxPoli, {
                    type: 'doughnut',
                    data: {
                        labels: poliLabels,
                        datasets: [{
                            data: poliData,
                            backgroundColor: [
                                '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#6366f1'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        }

        // Initial render
        renderCharts();
    </script>
</div>
