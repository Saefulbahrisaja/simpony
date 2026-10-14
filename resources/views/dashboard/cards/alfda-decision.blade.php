@php
    $alfda = $latestAlfda ?? null;
    $decision = $alfda?->decision;

    $nutrientStatus = match ($decision) {
        'CHECK_NUTRIENT', 'CHECK_BOTH'
            => 'Nutrisi Perlu Pemeriksaan',
        'SENSOR_INVALID'
            => 'Sensor Tidak Valid',
        null
            => 'Menunggu Data',
        default
            => 'Tidak Ada Peringatan Nutrisi',
    };

    $waterStatus = match ($decision) {
        'CHECK_WATER', 'CHECK_BOTH'
            => 'Air Kurang',
        'SENSOR_INVALID'
            => 'Sensor Tidak Valid',
        null
            => 'Menunggu Data',
        default
            => 'Tidak Ada Peringatan Air',
    };

    $decisionLabel = $alfda
        ? ($alfda->message ?: $decision)
        : 'Belum Dihitung';
@endphp

{{-- CARD UTAMA ALFDA INTELLIGENT DECISION --}}
<section class="p-6 border border-slate-100 shadow-sm rounded-3xl bg-white hover:shadow-md transition-all duration-300 space-y-5">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20">
                <i class="fas fa-brain"></i>
            </div>
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100/60">
                    Smart AI Decision Engine
                </span>
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mt-1">
                    ALFDA Intelligent Decision
                </h3>
            </div>
        </div>
<!--
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 text-amber-700 border border-amber-200/60 text-xs font-black self-start sm:self-auto">
            <span class="w-2 h-2 bg-amber-500 rounded-full animate-ping"></span>
            Mode Simulasi
        </span>-->
    </div>

    <!-- Decision Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Status Nutrisi -->
        <div class="p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-2.5 text-emerald-600 mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                    <i class="fas fa-flask"></i>
                </div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                    Status Nutrisi
                </span>
            </div>
            <p class="text-sm font-extrabold text-slate-800">
                {{ $nutrientStatus }}
            </p>
        </div>

        <!-- Status Air -->
        <div class="p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-2.5 text-sky-600 mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-sky-500/10 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                    <i class="fas fa-water"></i>
                </div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                    Status Air
                </span>
            </div>
            <p class="text-sm font-extrabold text-slate-800">
                {{ $waterStatus }}
            </p>
        </div>

        <!-- Keputusan ALFDA -->
        <div class="p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-2.5 text-indigo-600 mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                    <i class="fas fa-brain"></i>
                </div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                    Keputusan ALFDA
                </span>
            </div>
            <p class="text-sm font-extrabold text-slate-800">
                {{ $decisionLabel }}
            </p>
        </div>

        <!-- Waktu Inferensi -->
        <div class="p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-2.5 text-amber-600 mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-500/10 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                    Waktu Inferensi
                </span>
            </div>
            <p class="text-lg font-black text-slate-800">
                @if($alfda && $alfda->inference_time_ms !== null)
                    {{ number_format((float) $alfda->inference_time_ms, 4) }}
                    <span class="text-xs font-bold text-slate-400">ms</span>
                @else
                    — <span class="text-xs font-bold text-slate-400">ms</span>
                @endif
            </p>
        </div>

    </div>

    <!-- Footer Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-3 border-t border-slate-100">
        <p class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
            <i class="fas fa-info-circle text-amber-500"></i>
            Keputusan eksperimental, belum mengendalikan pompa otomatis.
        </p>

        <p class="text-[11px] font-bold text-slate-400">
            @if($alfda)
                Diperbarui: {{ $alfda->created_at?->format('d/m/Y H:i:s') }}
            @else
                Menunggu hasil kalkulasi ALFDA
            @endif
        </p>
    </div>

</section>