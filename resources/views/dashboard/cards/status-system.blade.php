{{-- CARD UTAMA STATUS SISTEM & AKTUATOR --}}
<div class="p-6 border border-slate-100 shadow-sm rounded-3xl bg-white hover:shadow-md transition-all duration-300">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100/60">
                Hardware Actuators
            </span>
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mt-2">
                <i class="fas fa-microchip text-emerald-600"></i>
                Status Sistem & Aktuator
            </h3>
        </div>
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20">
            <i class="fas fa-cogs animate-spin-slow"></i>
        </div>
    </div>

    {{-- Daftar Status Aktuator --}}
    <div class="space-y-3.5">
        {{-- Pompa Air --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-droplet"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Pompa Air Utama</p>
                    <p class="text-xs text-slate-400">Sirkulasi air nutrisi hidroponik</p>
                </div>
            </div>
            <div class="ml-auto">
                <div id="statusPompaAir" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold text-slate-500 bg-slate-200/80 transition-all">
                    <i class="fas fa-circle-notch animate-spin text-xs"></i>
                    <span>Connecting...</span>
                </div>
            </div>
        </div>

        {{-- Pompa Nutrisi --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-flask"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Pompa Dosis Nutrisi</p>
                    <p class="text-xs text-slate-400">Pemberian nutrisi otomatis (AB Mix)</p>
                </div>
            </div>
            <div class="ml-auto">
                <div id="statusPompaNutrisi" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold text-slate-500 bg-slate-200/80 transition-all">
                    <i class="fas fa-circle-notch animate-spin text-xs"></i>
                    <span>Connecting...</span>
                </div>
            </div>
        </div>

        {{-- Level Air --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-water"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Level Air Reservoir</p>
                    <p class="text-xs text-slate-400">Ketinggian air dalam tandon</p>
                </div>
            </div>
            <div class="ml-auto">
                <div id="statusLevelAir" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold text-slate-500 bg-slate-200/80 transition-all">
                    <i class="fas fa-circle-notch animate-spin text-xs"></i>
                    <span>Memuat...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    async function updateStatusSystem() {
        try {
            const res = await fetch('{{ url('/sensor/live') }}', { cache: 'no-store' });
            if (!res.ok) return;
            const data = await res.json();

            // Helper pembentuk Badge Pompa (ON / OFF)
            const updatePumpStatus = (elementId, isActive) => {
                const el = document.getElementById(elementId);
                if (!el) return;

                if (isActive) {
                    el.className = 'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black text-white bg-emerald-500 shadow-md shadow-emerald-500/30 transition-all';
                    el.innerHTML = `<i class="fas fa-fan animate-spin text-xs"></i> <span>AKTIF (ON)</span>`;
                } else {
                    el.className = 'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold text-slate-500 bg-slate-200/80 transition-all';
                    el.innerHTML = `<i class="fas fa-power-off text-xs text-slate-400"></i> <span>NONAKTIF</span>`;
                }
            };

            // Update Status Pompa Air & Nutrisi
            updatePumpStatus('statusPompaAir', Boolean(data.pompa_air));
            updatePumpStatus('statusPompaNutrisi', Boolean(data.pompa_nutrisi));

            // Update Status Level Air
            const elLevel = document.getElementById('statusLevelAir');
            if (elLevel) {
                const airMin = Number(data.air_min) || 0;
                const waterStat = Number(data.water_stat) || 0;

                if (waterStat > airMin) {
                    elLevel.className = 'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black text-white bg-rose-500 shadow-md shadow-rose-500/30 transition-all animate-bounce';
                    elLevel.innerHTML = `<i class="fas fa-exclamation-triangle text-xs"></i> <span>AIR KURANG</span>`;
                } else {
                    elLevel.className = 'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black text-white bg-teal-500 shadow-md shadow-teal-500/30 transition-all';
                    elLevel.innerHTML = `<i class="fas fa-circle-check text-xs"></i> <span>AIR CUKUP</span>`;
                }
            }

        } catch (err) {
            console.debug("Gagal memperbarui status sistem:", err);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateStatusSystem();
        setInterval(updateStatusSystem, 5000);
    });
})();
</script>
@endpush