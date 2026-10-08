@extends('layouts.app')

@section('title', 'Dashboard Hidroponik - SIKECE')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto px-3 sm:px-6 py-2">
    
    {{-- HEADER DASHBOARD --}}
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-2 border-b border-gray-200/60">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Dashboard Hidroponik</h1>
            <p class="text-xs sm:text-sm text-gray-500">Pemantauan real-time dan kontrol kondisi sistem hidroponik</p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-200/60">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            Sistem Aktif
        </div>
    </header>

    {{-- STATUS RINGKAS (METRICS SUMMARY) --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- CARD TDS --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i class="fas fa-droplet text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">TDS Terakhir</p>
                    <p class="text-2xl font-extrabold text-gray-900 leading-none mt-1">
                        <span id="dashboardTds">{{ $latestSensor?->tds !== null ? number_format($latestSensor->tds, 0) : '-' }}</span>
                        <span class="text-xs font-semibold text-gray-500 ml-0.5">ppm</span>
                    </p>
                </div>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></span>
        </div>

        {{-- CARD SUHU AIR --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i class="fas fa-temperature-half text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Suhu Air</p>
                    <p class="text-2xl font-extrabold text-gray-900 leading-none mt-1">
                        <span id="dashboardSuhu">{{ $latestSensor?->suhu !== null ? number_format($latestSensor->suhu, 1) : '-' }}</span>
                        <span class="text-xs font-semibold text-gray-500 ml-0.5">°C</span>
                    </p>
                </div>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-sky-500 ring-4 ring-sky-50"></span>
        </div>

        {{-- CARD KELEMBABAN --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i class="fas fa-wind text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kelembaban</p>
                    <p class="text-2xl font-extrabold text-gray-900 leading-none mt-1">
                        <span id="dashboardHumidity">{{ $latestSensor?->kelembaban !== null ? number_format($latestSensor->kelembaban, 1) : '-' }}</span>
                        <span class="text-xs font-semibold text-gray-500 ml-0.5">%</span>
                    </p>
                </div>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-teal-500 ring-4 ring-teal-50"></span>
        </div>
    </section>

    {{-- MONITORING SISTEM --}}
    <section class="w-full">
        @include('dashboard.cards.status-system')
    </section>

    {{-- ACTIVE CROP, MANAJEMEN TANAMAN & TELEMETRY --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
        <div class="xl:col-span-1">
            @include('dashboard.cards.kondisi-tanaman')
        </div>
        <div class="xl:col-span-2 space-y-4">
            @include('dashboard.cards.manajemen-tanaman')
            @include('dashboard.metrics.live-sensor')
        </div>
    </section>

    {{-- FILTER + GRAFIK --}}
    <section class="bg-white rounded-2xl border border-gray-100 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Historical Monitoring</p>
                <h2 class="text-lg font-extrabold text-gray-900 mt-0.5">Tren Sensor</h2>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <select id="filterSelector" class="appearance-none text-xs font-semibold bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-3.5 py-2 pr-8 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition cursor-pointer">
                        <option value="hari" {{ $filter === 'hari' ? 'selected' : '' }}>Hari ini</option>
                        <option value="minggu" {{ $filter === 'minggu' ? 'selected' : '' }}>Minggu ini</option>
                        <option value="bulan" {{ $filter === 'bulan' ? 'selected' : '' }}>Bulan ini</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-500">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
                <button id="exportBtn" type="button" class="inline-flex items-center gap-2 text-xs bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-2 px-4 rounded-xl shadow-sm transition-colors">
                    <i class="fas fa-download text-xs"></i>
                    <span>Export Data</span>
                </button>
            </div>
        </div>
        @include('dashboard.cards.chart-sensor')
    </section>

    {{-- SYSTEM CARDS --}}
    @include('dashboard.cards.pengaturan-system')
</div>
@endsection

@push('scripts')
@include('layouts.partials.scripts')
<script>
(function () {
    const filter = document.getElementById('filterSelector');
    const exportBtn = document.getElementById('exportBtn');
    if (filter) filter.addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('filter', this.value);
        window.location.href = url.toString();
    });
    if (exportBtn) exportBtn.addEventListener('click', function () {
        window.location.href = '{{ url('/export-excel') }}?filter=' + encodeURIComponent(filter?.value || 'hari');
    });

    async function refreshDashboardSummary() {
        try {
            const r = await fetch('{{ url('/sensor/live') }}', {cache:'no-store'});
            if (!r.ok) return;
            const d = await r.json();
            const set = (id, value) => { const el=document.getElementById(id); if(el && value !== undefined && value !== null) el.textContent=value; };
            if (Array.isArray(d.tds) && d.tds.length) set('dashboardTds', Number(d.tds[d.tds.length-1]).toFixed(0));
            if (Array.isArray(d.suhu) && d.suhu.length) set('dashboardSuhu', Number(d.suhu[d.suhu.length-1]).toFixed(1));
            if (Array.isArray(d.kelembaban) && d.kelembaban.length) set('dashboardHumidity', Number(d.kelembaban[d.kelembaban.length-1]).toFixed(1));
        } catch(e) { console.debug('Dashboard summary:', e); }
    }
    refreshDashboardSummary();
    setInterval(refreshDashboardSummary, 10000);
})();
</script>
@endpush
