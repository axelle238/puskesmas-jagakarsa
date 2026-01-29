<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Laporan Morbiditas (LB1)</h1>
        <p class="text-slate-500">Rekapitulasi 10 besar penyakit terbanyak berdasarkan diagnosa ICD-10.</p>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-6 flex gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Bulan</label>
            <select wire:model.live="bulan" class="rounded-lg border-slate-300 text-sm">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ sprintf('%02d', $i) }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">Tahun</label>
            <select wire:model.live="tahun" class="rounded-lg border-slate-300 text-sm">
                @for($i=date('Y'); $i>=date('Y')-5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="ml-auto">
            <button onclick="window.print()" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Visualisasi Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-6 lg:col-span-1">
            <h3 class="font-bold text-slate-800 mb-4 text-center">Proporsi Kasus</h3>
            <div class="h-64">
                <canvas id="diseaseChart"></canvas>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden lg:col-span-2 h-fit">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Peringkat</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode ICD-10</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Diagnosa</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($laporan as $index => $l)
                    <tr>
                        <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-emerald-600 font-bold">{{ $l->diagnosis_kode }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $l->asesmen }}</td>
                        <td class="px-6 py-4 text-right text-sm font-black text-slate-900">{{ $l->total }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data diagnosa pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let diseaseChartInstance = null;

        document.addEventListener('livewire:navigated', () => { renderDiseaseChart(); });
        document.addEventListener('livewire:updated', () => { renderDiseaseChart(); });

        function renderDiseaseChart() {
            const labels = @json($laporan->pluck('asesmen'));
            const data = @json($laporan->pluck('total'));

            if (diseaseChartInstance) diseaseChartInstance.destroy();

            const ctx = document.getElementById('diseaseChart');
            if (ctx) {
                diseaseChartInstance = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                '#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981', 
                                '#06b6d4', '#3b82f6', '#6366f1', '#8b5cf6', '#d946ef'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                        }
                    }
                });
            }
        }
        renderDiseaseChart();
    </script>
</div>
