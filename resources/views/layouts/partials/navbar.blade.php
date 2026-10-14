<header class="fixed top-0 left-0 w-full z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800 text-white shadow-md transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
        
        <!-- Brand Identity (Gaya Floating Badge Cyber) -->
        <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
            <div class="relative">
                <!-- Glowing Gradient Border Neon Cyan-Emerald -->
                <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-emerald-500 rounded-2xl blur-md opacity-30 group-hover:opacity-100 transition duration-500"></div>
                
                <!-- CONTAINER LOGO YANG DIPERBAIKI (Perbesar ukuran & hapus padding) -->
                <div class="relative w-16 h-16 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center shadow-inner overflow-hidden">
                    <!-- GAMBAR LOGO YANG DIPERBAIKI (Ganti object-cover dengan object-contain) -->
                    <img src="{{ asset('logo.png') }}" alt="Logo SIMPONI" class="w-full h-full object-contain rounded-lg">
                </div>
            </div>

            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <!-- Text Glowing Neon (Ganti Animasi Blink Jadul) -->
                    <span class="text-xl font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-white via-emerald-200 to-emerald-400 simponi-glow-text">
                        SIMPONI
                    </span>
                    <span class="text-[9px] font-black tracking-widest uppercase bg-gradient-to-r from-emerald-500 to-teal-500 text-slate-950 px-2 py-0.5 rounded-full shadow-sm shadow-emerald-500/50">
                        PRO V1.0
                    </span>
                </div>

                <!-- Running Subtitle (Efek Marquee Modern + Gradient Mask) -->
                <div class="w-52 overflow-hidden mt-0.5 mask-gradient">
                    <p class="text-[10px] font-semibold tracking-wide text-emerald-400/80 uppercase whitespace-nowrap animate-slide-bounce">
                        • Smart Hydroponic Nutrition System • Real-Time IoT Monitoring
                    </p>
                </div>
            </div>
        </a>

        <!-- System Status Badge -->
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-xs text-emerald-400 font-bold">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>System Online</span>
            </div>
        </div>
    </div>
</header>

<!-- CUSTOM CSS UNTUK IDENTITAS VISUAL BARU SIMPONI -->
<style>
    /* Glowing Effect Tulisan SIMPONI */
    .simponi-glow-text {
        text-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
        animation: pulseGlow 3s ease-in-out infinite alternate;
    }

    @keyframes pulseGlow {
        0% {
            text-shadow: 0 0 8px rgba(16, 185, 129, 0.3);
            filter: brightness(1);
        }
        100% {
            text-shadow: 0 0 18px rgba(16, 185, 129, 0.8), 0 0 28px rgba(20, 184, 166, 0.5);
            filter: brightness(1.2);
        }
    }

    /* Animasi Teks Berjalan Halus (Slide Bounce) */
    @keyframes slideBounce {
        0%, 100% {
            transform: translateX(0%);
        }
        50% {
            transform: translateX(-40%);
        }
    }

    .animate-slide-bounce {
        display: inline-block;
        animation: slideBounce 8s ease-in-out infinite;
    }

    /* Masking gradien halus di tepi kiri/kanan teks jalan */
    .mask-gradient {
        mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }
</style>