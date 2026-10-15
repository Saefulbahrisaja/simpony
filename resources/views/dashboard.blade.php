<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPONI - (Smart Hydroponic Center)</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(ellipse at top, #0f2027 0%, #203a43 50%, #0c1017 100%);
            color: #f8fafc;
        }

        /* Glassmorphism Effect */
        .glass-box {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        /* Neon Glow Effect */
        .glow-emerald {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.25);
        }
        .glow-cyan {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.25);
        }

        /* Water Tank Animation */
        .water-container {
            position: relative;
            background: rgba(15, 23, 42, 0.8);
            border-radius: 1rem;
            overflow: hidden;
        }
        .water-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 65%; /* Persentase air */
            background: linear-gradient(180deg, rgba(6, 182, 212, 0.6) 0%, rgba(14, 165, 233, 0.9) 100%);
            transition: height 0.5s ease;
        }

        /* Pulsing dot */
        .pulse-emerald {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
</head>
<body class="min-h-screen pt-24 pb-12 px-4 sm:px-8">

    <!-- Included Navbar -->
    @include('navbar')

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- AI Banner / Quick Stats Header -->
        <div class="glass-box rounded-3xl p-6 glow-emerald relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 border-l-4 border-emerald-500">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 text-2xl font-bold shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400">AI Agronomist Active</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    </div>
                    <h2 class="text-xl font-bold text-white mt-0.5">Sistem Dalam Kondisi Optimal</h2>
                    <p class="text-xs text-slate-400">Estimasi waktu pemeliharaan nutrisi berikutnya: <span class="text-slate-200 font-semibold">14:00 WIB</span></p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <button onclick="updateStatusSystem()" class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt text-emerald-400"></i> Refresh Sensor
                </button>
            </div>
        </div>

        <!-- Metric Live Sensors Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- TDS Card -->
            <div class="glass-box rounded-2xl p-5 relative overflow-hidden group hover:border-emerald-500/50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kadar Nutrisi (TDS)</p>
                        <h3 id="tds" class="text-3xl font-extrabold text-white mt-2">-- <span class="text-sm font-normal text-slate-400">ppm</span></h3>
                    </div>
                    <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-400">
                        <i data-lucide="leaf" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-800 flex justify-between items-center text-xs text-slate-400">
                    <span>Target Ideal: 800-1200</span>
                    <span class="text-emerald-400 font-semibold"><i class="fas fa-check-circle"></i> Normal</span>
                </div>
            </div>

            <!-- Suhu Air Card -->
            <div class="glass-box rounded-2xl p-5 relative overflow-hidden group hover:border-rose-500/50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Suhu Air Nutrisi</p>
                        <h3 id="suhuAir" class="text-3xl font-extrabold text-white mt-2">-- <span class="text-sm font-normal text-slate-400">°C</span></h3>
                    </div>
                    <div class="p-3 bg-rose-500/10 rounded-xl text-rose-400">
                        <i data-lucide="thermometer" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-800 flex justify-between items-center text-xs text-slate-400">
                    <span>Batas Maks: 28°C</span>
                    <span class="text-rose-400 font-semibold"><i class="fas fa-fire"></i> Teratur</span>
                </div>
            </div>

            <!-- Suhu Udara Card -->
            <div class="glass-box rounded-2xl p-5 relative overflow-hidden group hover:border-amber-500/50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Suhu Udara</p>
                        <h3 id="suhuudara" class="text-3xl font-extrabold text-white mt-2">-- <span class="text-sm font-normal text-slate-400">°C</span></h3>
                    </div>
                    <div class="p-3 bg-amber-500/10 rounded-xl text-amber-400">
                        <i data-lucide="sun" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-800 flex justify-between items-center text-xs text-slate-400">
                    <span>Ruang Tumbuh</span>
                    <span class="text-slate-300">Stabil</span>
                </div>
            </div>

            <!-- Kelembaban Card -->
            <div class="glass-box rounded-2xl p-5 relative overflow-hidden group hover:border-cyan-500/50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kelembaban Udara</p>
                        <h3 id="kelembaban" class="text-3xl font-extrabold text-white mt-2">-- <span class="text-sm font-normal text-slate-400">%</span></h3>
                    </div>
                    <div class="p-3 bg-cyan-500/10 rounded-xl text-cyan-400">
                        <i data-lucide="droplets" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-800 flex justify-between items-center text-xs text-slate-400">
                    <span>Humiditas Lingkungan</span>
                    <span class="text-cyan-400 font-semibold">Optimal</span>
                </div>
            </div>
        </div>

        <!-- Actuators & Reservoir Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Actuators Control Status -->
            <div class="glass-box rounded-3xl p-6 lg:col-span-2 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                    <h3 class="font-bold text-lg text-white flex items-center gap-2">
                        <i class="fas fa-sliders-h text-emerald-400"></i> Kontrol Actuator Hardware
                    </h3>
                    <span class="text-xs bg-slate-800 text-slate-400 px-3 py-1 rounded-full border border-slate-700">Auto Mode</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Pompa Air -->
                    <div class="p-4 rounded-2xl bg-slate-900/50 border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                                <i class="fas fa-water"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Pompa Utama</h4>
                                <p class="text-xs text-slate-400">Sirkulasi Air</p>
                            </div>
                        </div>
                        <div id="statusPompaAir">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-500">OFF</span>
                        </div>
                    </div>

                    <!-- Pompa Nutrisi -->
                    <div class="p-4 rounded-2xl bg-slate-900/50 border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                                <i class="fas fa-flask"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Pompa Nutrisi</h4>
                                <p class="text-xs text-slate-400">Dosing AB Mix</p>
                            </div>
                        </div>
                        <div id="statusPompaNutrisi">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-500">OFF</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Water Reservoir Tank -->
            <div class="glass-box rounded-3xl p-6 flex flex-col justify-between water-container glow-cyan">
                <div class="water-wave"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-200">Level Tangki Air</h4>
                        <p class="text-2xl font-black text-white mt-1">Reservoir Utama</p>
                    </div>
                    <span id="statusLevelAir" class="px-3 py-1 rounded-full text-xs font-bold bg-cyan-950 text-cyan-300 border border-cyan-500/30">
                        Mengecek...
                    </span>
                </div>
                
                <div class="relative z-10 mt-12 pt-4 border-t border-cyan-500/20 flex justify-between items-center text-xs text-cyan-100">
                    <span>Batas Minimal: 10 cm</span>
                    <span class="font-bold text-white">Kapasitas Aman</span>
                </div>
            </div>

        </div>

        <!-- Main Chart -->
        <div class="glass-box rounded-3xl p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="font-bold text-lg text-white">Monitoring Telemetri Real-time</h3>
                    <p class="text-xs text-slate-400">Grafik riwayat parameter lingkungan dan nutrisi hidroponik</p>
                </div>
            </div>
            <div class="h-80">
                <canvas id="combinedChart"></canvas>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();

        // Chart Initialization
        const ctx = document.getElementById('combinedChart').getContext('2d');
        const combinedChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['10:00', '10:05', '10:10', '10:15', '10:20', '10:25'],
                datasets: [
                    { label: 'TDS (ppm)', data: [820, 830, 825, 840, 850, 845], borderColor: '#10b981', tension: 0.4 },
                    { label: 'Suhu Air (°C)', data: [25, 25.2, 25.1, 25.3, 25.4, 25.2], borderColor: '#f43f5e', tension: 0.4 },
                    { label: 'Suhu Udara (°C)', data: [29, 29.5, 30, 30.2, 29.8, 29.5], borderColor: '#f59e0b', tension: 0.4 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#94a3b8' } } },
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } },
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } }
                }
            }
        });
    </script>
</body>
</html>