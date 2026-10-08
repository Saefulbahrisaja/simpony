@extends('layouts.app')

@section('title', 'Dashboard Hidroponik - SIKECE')

@section('content')

<div class="space-y-4 max-w-7xl mx-auto px-2 sm:px-4">
    {{-- STATUS RINGKAS (COMPACT) --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        {{-- TDS --}}
        <div class="flex items-center justify-between p-3.5 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl text-white shadow-sm hover:shadow transition">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-droplet text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-emerald-100">TDS Terakhir</p>
                    <p class="text-xl font-bold tracking-tight">
                        <span id="dashboardTds">{{ $latestSensor?->tds !== null ? number_format($latestSensor->tds, 0) : '-' }}</span>
                        <span class="text-xs font-normal opacity-90">ppm</span>
                    </p>
                </div>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-200 animate-pulse"></span>
        </div>

        {{-- SUHU AIR --}}
        <div class="flex items-center justify-between p-3.5 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl text-white shadow-sm hover:shadow transition">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-temperature-half text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-teal-100">Suhu Air</p>
                    <p class="text-xl font-bold tracking-tight">
                        <span id="dashboardSuhu">{{ $latestSensor?->suhu !== null ? number_format($latestSensor->suhu, 1) : '-' }}</span>
                        <span class="text-xs font-normal opacity-90">°C</span>
                    </p>
                </div>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-teal-200 animate-pulse"></span>
        </div>

        {{-- KELEMBABAN --}}
        <div class="flex items-center justify-between p-3.5 bg-gradient-to-br from-cyan-600 to-emerald-700 rounded-xl text-white shadow-sm hover:shadow transition">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-wind text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-cyan-100">Kelembaban</p>
                    <p class="text-xl font-bold tracking-tight">
                        <span id="dashboardHumidity">{{ $latestSensor?->kelembaban !== null ? number_format($latestSensor->kelembaban, 1) : '-' }}</span>
                        <span class="text-xs font-normal opacity-90">%</span>
                    </p>
                </div>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-200 animate-pulse"></span>
        </div>
    </section>

    {{-- MONITORING SISTEM --}}
    <section class="w-full">
        @include('dashboard.cards.status-system')
    </section>

    {{-- TANAMAN & LIVE SENSOR --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="xl:col-span-1">@include('dashboard.cards.kondisi-tanaman')</div>
        <div class="xl:col-span-2">@include('dashboard.metrics.live-sensor')</div>
    </section>

    {{-- FILTER + GRAFIK --}}
    <section class="bg-white rounded-xl border border-emerald-100 p-4 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Historical Monitoring</p>
                <h2 class="text-base font-bold text-gray-800">Tren Sensor</h2>
            </div>
            <div class="flex items-center gap-2">
                <select id="filterSelector" class="text-xs border border-emerald-200 rounded-lg px-2.5 py-1.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-emerald-50/50 text-emerald-900 font-medium">
                    <option value="hari" {{ $filter === 'hari' ? 'selected' : '' }}>Hari ini</option>
                    <option value="minggu" {{ $filter === 'minggu' ? 'selected' : '' }}>Minggu ini</option>
                    <option value="bulan" {{ $filter === 'bulan' ? 'selected' : '' }}>Bulan ini</option>
                </select>
                <button id="exportBtn" type="button" class="inline-flex items-center gap-1.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-1.5 px-3 rounded-lg shadow-sm transition">
                    <i class="fas fa-download text-xs"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>
        @include('dashboard.cards.chart-sensor')
    </section>

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