{{-- CARD UTAMA PREDIKSI & ANALISIS --}}
<div class="p-6 border border-slate-100 shadow-sm rounded-3xl bg-white hover:shadow-md transition-all duration-300">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100/60">
                Smart AI Insights
            </span>
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mt-2">
                <i class="fas fa-brain text-emerald-600"></i>
                Prediksi & Analisis
            </h3>
        </div>
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20">
            <i class="fas fa-chart-pie"></i>
        </div>
    </div>

    {{-- Daftar Indikator Analisis --}}
    <div class="space-y-3.5">
        
        {{-- Prediksi Kondisi Lingkungan --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-cloud-sun"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Kondisi Lingkungan</p>
                    <p class="text-xs text-slate-400">Analisis suhu & kelembaban</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="prediksiLingkungan" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span>.</span><span>.</span><span>.</span></span>
                    <span class="ml-1">Menganalisis...</span>
                </div>
            </div>
        </div>

        {{-- Deteksi Penyakit --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-virus"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Deteksi Penyakit</p>
                    <p class="text-xs text-slate-400">Status kesehatan sampel daun</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="deteksiPenyakit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span>.</span><span>.</span><span>.</span></span>
                    <span class="ml-1">Memeriksa...</span>
                </div>
            </div>
        </div>

        {{-- Perkiraan Panen --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Perkiraan Panen</p>
                    <p class="text-xs text-slate-400">Estimasi masa panen tanaman</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="prediksiPanen" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span>.</span><span>.</span><span>.</span></span>
                    <span class="ml-1">Menghitung...</span>
                </div>
            </div>
        </div>

        {{-- Pemakaian Air & Nutrisi --}}
        <div class="flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/60 rounded-2xl border border-slate-100/80 transition-all duration-200 group">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-droplet"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Pemakaian Air & Nutrisi</p>
                    <p class="text-xs text-slate-400">Estimasi konsumsi cairan</p>
                </div>
            </div>
            <div class="ml-auto text-right min-w-0">
                <div id="prediksiAirNutrisi" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="thinking-dots"><span>.</span><span>.</span><span>.</span></span>
                    <span class="ml-1">Mengkalkulasi...</span>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Fungsi Global Prediksi Panen
function updatePrediksiPanen(tanaman, tanamDate) {
    const elemenPanen = document.getElementById('prediksiPanen');
    if (!elemenPanen) return;

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (tanamDate && tanaman && tanaman.masa_tumbuh) {
        const masaTumbuh = Number(tanaman.masa_tumbuh);
        const perkiraanPanenDate = new Date(tanamDate);
        perkiraanPanenDate.setDate(perkiraanPanenDate.getDate() + masaTumbuh);

        if (isNaN(perkiraanPanenDate.getTime())) {
            elemenPanen.innerHTML = `<span class="text-xs text-slate-400 italic">Format tanggal salah</span>`;
            return;
        }

        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        const tanggalPanenStr = perkiraanPanenDate.toLocaleDateString('id-ID', options);

        // Hitung sisa hari
        const sisaHari = Math.ceil((perkiraanPanenDate - today) / (1000 * 60 * 60 * 24));

        if (sisaHari <= 0) {
            elemenPanen.innerHTML = `
                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-700 border border-emerald-200/60 font-black text-xs">
                    <i class="fas fa-wheat-awn text-emerald-600"></i>
                    <span>Siap Panen!</span>
                </div>
            `;
        } else if (sisaHari <= 3) {
            elemenPanen.innerHTML = `
                <div class="text-right">
                    <span class="block text-xs font-black text-rose-600 animate-pulse">${sisaHari} Hari Lagi</span>
                    <span class="block text-[10px] font-bold text-slate-400">${tanggalPanenStr}</span>
                </div>
            `;
        } else if (sisaHari <= 7) {
            elemenPanen.innerHTML = `
                <div class="text-right">
                    <span class="block text-xs font-black text-amber-600">${sisaHari} Hari Lagi</span>
                    <span class="block text-[10px] font-bold text-slate-400">${tanggalPanenStr}</span>
                </div>
            `;
        } else {
            elemenPanen.innerHTML = `
                <div class="text-right">
                    <span class="block text-xs font-black text-emerald-600">${sisaHari} Hari Lagi</span>
                    <span class="block text-[10px] font-bold text-slate-400">${tanggalPanenStr}</span>
                </div>
            `;
        }
    } else {
        elemenPanen.innerHTML = `<span class="text-xs text-slate-400 italic">Data tanam belum ada</span>`;
    }
}

// Module Analisis AI Realtime
(function () {
    async function loadAnalysisData() {
        try {
            const res = await fetch('{{ url('/sensor/live') }}', { cache: 'no-store' });
            if (!res.ok) return;
            const data = await res.json();

            const lastVal = (arr) => (Array.isArray(arr) && arr.length) ? Number(arr[arr.length - 1]) : null;

            const suhuAir = lastVal(data.suhu);
            const suhuUdara = lastVal(data.suhuudara);
            const kelembaban = lastVal(data.kelembaban);
            const tds = lastVal(data.tds);

            // 1. Analisis Kondisi Lingkungan
            const elLingkungan = document.getElementById('prediksiLingkungan');
            if (elLingkungan) {
                if (suhuUdara !== null && kelembaban !== null) {
                    if (suhuUdara >= 22 && suhuUdara <= 30 && kelembaban >= 50 && kelembaban <= 80) {
                        elLingkungan.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-700 border border-emerald-200/60">
                                <i class="fas fa-circle-check text-[10px] mr-1"></i> Optimal
                            </span>
                        `;
                    } else if (suhuUdara > 32) {
                        elLingkungan.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-500/10 text-amber-700 border border-amber-200/60">
                                <i class="fas fa-temperature-arrow-up text-[10px] mr-1"></i> Udara Panas
                            </span>
                        `;
                    } else {
                        elLingkungan.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-sky-500/10 text-sky-700 border border-sky-200/60">
                                <i class="fas fa-circle-info text-[10px] mr-1"></i> Normal
                            </span>
                        `;
                    }
                } else {
                    elLingkungan.innerHTML = `<span class="text-xs text-slate-400">Sensor Offline</span>`;
                }
            }

            // 2. Analisis Deteksi Penyakit
            const elPenyakit = document.getElementById('deteksiPenyakit');
            if (elPenyakit) {
                if (data.latest_disease) {
                    const diseaseName = data.latest_disease.disease || 'Sehat';
                    if (diseaseName.toLowerCase().includes('healthy') || diseaseName.toLowerCase().includes('sehat')) {
                        elPenyakit.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-700 border border-emerald-200/60">
                                <i class="fas fa-shield-halved text-[10px] mr-1"></i> Tanaman Sehat
                            </span>
                        `;
                    } else {
                        elPenyakit.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-rose-500/10 text-rose-700 border border-rose-200/60">
                                <i class="fas fa-bug text-[10px] mr-1"></i> ${diseaseName}
                            </span>
                        `;
                    }
                } else {
                    elPenyakit.innerHTML = `
                        <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-700 border border-emerald-200/60">
                            <i class="fas fa-check text-[10px] mr-1"></i> Bebas Hama
                        </span>
                    `;
                }
            }

            // 3. Analisis Pemakaian Air & Nutrisi
            const elAirNutrisi = document.getElementById('prediksiAirNutrisi');
            if (elAirNutrisi) {
                if (tds !== null) {
                    const tdsMin = Number(data.tds_min) || 600;
                    if (tds < tdsMin) {
                        elAirNutrisi.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-500/10 text-amber-700 border border-amber-200/60">
                                <i class="fas fa-triangle-exclamation text-[10px] mr-1"></i> Perlu Dosing
                            </span>
                        `;
                    } else {
                        elAirNutrisi.innerHTML = `
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-teal-500/10 text-teal-700 border border-teal-200/60">
                                <i class="fas fa-droplet text-[10px] mr-1"></i> Efisien
                            </span>
                        `;
                    }
                } else {
                    elAirNutrisi.innerHTML = `<span class="text-xs text-slate-400">Normal</span>`;
                }
            }

        } catch (e) {
            console.debug("Gagal memuat analisis AI:", e);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadAnalysisData();
        setInterval(loadAnalysisData, 10000);
    });
})();
</script>
@endpush