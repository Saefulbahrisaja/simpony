<footer class="mt-auto bg-slate-900 border-t border-slate-800 text-slate-400 py-8 text-xs">
    <div class="container mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <!-- Left Section: Brand & Copyright -->
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                <i class="fas fa-leaf text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-slate-200">SIMPONI &copy; {{ date('Y') }}</p>
                <p class="text-[11px] text-slate-500">Smart IoT-Based Hydroponic Nutrition System</p>
            </div>
        </div>

        <!-- Center Section: Quick Links / Status -->
        <div class="flex items-center gap-6 font-medium text-slate-400">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                System Operational
            </span>
            <span class="text-slate-700">|</span>
            <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
        </div>

        <!-- Right Section: Social / Info -->
        <div class="text-center sm:text-right text-slate-500">
            <p>Dikembangkan untuk Presisi Nutrisi Hidroponik</p>
        </div>

    </div>
</footer>