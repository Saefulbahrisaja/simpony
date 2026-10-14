{{-- CARD UTAMA GRAFIK SENSOR --}}
<div class="p-6 border border-slate-100 shadow-sm rounded-3xl bg-white hover:shadow-md transition-all duration-300">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100/60">
                Realtime Analytics
            </span>
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mt-2">
                <i class="fas fa-chart-line text-emerald-600"></i>
                Grafik Telemetri Sensor
            </h3>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200/80 self-start sm:self-auto shadow-2xs">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
            Auto update tiap 10 detik
        </div>
    </div>

    {{-- Grid Grafik Individu --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Suhu Air --}}
        <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100/90 hover:bg-white hover:shadow-sm transition-all duration-200 group">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-slate-700 flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-temperature-high"></i>
                    </span>
                    Suhu Air Nutrisi
                </h4>
                <span class="text-[11px] font-extrabold text-rose-600 bg-rose-500/10 px-2.5 py-1 rounded-lg">°C</span>
            </div>
            <div class="relative w-full h-56">
                <canvas id="suhuChart"></canvas>
            </div>
        </div>

        {{-- Suhu Udara --}}
        <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100/90 hover:bg-white hover:shadow-sm transition-all duration-200 group">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-slate-700 flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-sun"></i>
                    </span>
                    Suhu Udara
                </h4>
                <span class="text-[11px] font-extrabold text-sky-600 bg-sky-500/10 px-2.5 py-1 rounded-lg">°C</span>
            </div>
            <div class="relative w-full h-56">
                <canvas id="suhuUdaraChart"></canvas>
            </div>
        </div>

        {{-- TDS Nutrisi --}}
        <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100/90 hover:bg-white hover:shadow-sm transition-all duration-200 group">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-slate-700 flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-leaf"></i>
                    </span>
                    Kepekatan Nutrisi (TDS)
                </h4>
                <span class="text-[11px] font-extrabold text-emerald-600 bg-emerald-500/10 px-2.5 py-1 rounded-lg">PPM</span>
            </div>
            <div class="relative w-full h-56">
                <canvas id="tdsChart"></canvas>
            </div>
        </div>

        {{-- Kelembaban Udara --}}
        <div class="bg-slate-50/60 rounded-2xl p-5 border border-slate-100/90 hover:bg-white hover:shadow-sm transition-all duration-200 group">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-slate-700 flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-wind"></i>
                    </span>
                    Kelembaban Udara
                </h4>
                <span class="text-[11px] font-extrabold text-indigo-600 bg-indigo-500/10 px-2.5 py-1 rounded-lg">% RH</span>
            </div>
            <div class="relative w-full h-56">
                <canvas id="kelembabanUdaraChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Gabungan Sensor --}}
    <div class="bg-slate-50/80 rounded-2xl p-5 md:p-6 border border-slate-100">
        <div class="flex items-center justify-between mb-5">
            <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center text-xs">
                    <i class="fas fa-chart-area"></i>
                </span>
                Grafik Monitoring Combined Multivariable
            </h4>
        </div>
        <div class="relative w-full h-80 md:h-96">
            <canvas id="combinedChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    let suhuChart, tdsChart, phChart, combinedChart, suhuUdaraChart, kelembabanUdaraChart;

    // Helper membuat gradien warnaChart.js
    function createGradient(ctx, colorHex) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, colorHex.replace('1)', '0.25)'));
        gradient.addColorStop(1, colorHex.replace('1)', '0.0)'));
        return gradient;
    }

    function createChartConfig(ctx, label, data, waktu, colorHex) {
        return {
            type: 'line',
            data: {
                labels: waktu || [],
                datasets: [{
                    label: label,
                    data: data || [],
                    borderColor: colorHex,
                    backgroundColor: createGradient(ctx, colorHex),
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    pointBackgroundColor: colorHex
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, family: 'Plus Jakarta Sans' }, color: '#94a3b8' }
                    },
                    y: {
                        beginAtZero: false,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 10, family: 'Plus Jakarta Sans' }, color: '#94a3b8' }
                    }
                }
            }
        };
    }

    function initCharts(waktu, suhu, tds, ph, suhuudara, kelembaban) {
        const ctxSuhu = document.getElementById('suhuChart')?.getContext('2d');
        const ctxTds = document.getElementById('tdsChart')?.getContext('2d');
        const ctxPh = document.getElementById('phChart')?.getContext('2d');
        const ctxSuhuUdara = document.getElementById('suhuUdaraChart')?.getContext('2d');
        const ctxKelembaban = document.getElementById('kelembabanUdaraChart')?.getContext('2d');
        const ctxCombined = document.getElementById('combinedChart')?.getContext('2d');

        if (ctxSuhu) suhuChart = new Chart(ctxSuhu, createChartConfig(ctxSuhu, 'Suhu Air (°C)', suhu, waktu, 'rgba(244, 63, 94, 1)'));
        if (ctxTds) tdsChart = new Chart(ctxTds, createChartConfig(ctxTds, 'TDS (ppm)', tds, waktu, 'rgba(16, 185, 129, 1)'));
        if (ctxPh) phChart = new Chart(ctxPh, createChartConfig(ctxPh, 'pH', ph, waktu, 'rgba(245, 158, 11, 1)'));
        if (ctxSuhuUdara) suhuUdaraChart = new Chart(ctxSuhuUdara, createChartConfig(ctxSuhuUdara, 'Suhu Udara (°C)', suhuudara, waktu, 'rgba(14, 165, 233, 1)'));
        if (ctxKelembaban) kelembabanUdaraChart = new Chart(ctxKelembaban, createChartConfig(ctxKelembaban, 'Kelembaban Udara (%)', kelembaban, waktu, 'rgba(99, 102, 241, 1)'));

        if (ctxCombined) {
            combinedChart = new Chart(ctxCombined, {
                type: 'line',
                data: {
                    labels: waktu || [],
                    datasets: [
                        { label: 'Suhu Air (°C)', data: suhu || [], borderColor: '#f43f5e', tension: 0.35, borderWidth: 2, pointRadius: 0 },
                        { label: 'TDS (ppm)', data: tds || [], borderColor: '#10b981', tension: 0.35, borderWidth: 2, pointRadius: 0 },
                        { label: 'Suhu Udara (°C)', data: suhuudara || [], borderColor: '#0ea5e9', tension: 0.35, borderWidth: 2, pointRadius: 0 },
                        { label: 'Kelembaban (%)', data: kelembaban || [], borderColor: '#6366f1', tension: 0.35, borderWidth: 2, pointRadius: 0 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 11, family: 'Plus Jakarta Sans' }, usePointStyle: true } }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10, family: 'Plus Jakarta Sans' }, color: '#94a3b8' } },
                        y: { beginAtZero: false, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10, family: 'Plus Jakarta Sans' }, color: '#94a3b8' } }
                    }
                }
            });
        }
    }

    async function fetchDataAndUpdateCharts() {
        try {
            const response = await fetch('{{ url('/sensor/live') }}', { cache: 'no-store' });
            if (!response.ok) return;
            const result = await response.json();
            
            const { waktu = [], suhu = [], tds = [], ph = [], suhuudara = [], kelembaban = [] } = result;

            const updateSingleChart = (chart, data) => {
                if (!chart) return;
                chart.data.labels = waktu;
                chart.data.datasets[0].data = data;
                chart.update('none');
            };

            updateSingleChart(suhuChart, suhu);
            updateSingleChart(tdsChart, tds);
            updateSingleChart(phChart, ph);
            updateSingleChart(suhuUdaraChart, suhuudara);
            updateSingleChart(kelembabanUdaraChart, kelembaban);

            if (combinedChart) {
                combinedChart.data.labels = waktu;
                if (combinedChart.data.datasets[0]) combinedChart.data.datasets[0].data = suhu;
                if (combinedChart.data.datasets[1]) combinedChart.data.datasets[1].data = tds;
                if (combinedChart.data.datasets[2]) combinedChart.data.datasets[2].data = suhuudara;
                if (combinedChart.data.datasets[3]) combinedChart.data.datasets[3].data = kelembaban;
                combinedChart.update('none');
            }
        } catch (e) {
            console.debug('Chart fetch update error:', e);
        }
    }

    fetch('{{ url('/sensor/live') }}', { cache: 'no-store' })
        .then(res => res.json())
        .then(data => {
            initCharts(data.waktu, data.suhu, data.tds, data.ph, data.suhuudara, data.kelembaban);
        })
        .catch(err => console.debug('Chart init error:', err));

    setInterval(fetchDataAndUpdateCharts, 10000);
})();
</script>
@endpush