{{-- CARD UTAMA PENGATURAN SISTEM --}}
<div class="p-6 border border-slate-100 shadow-sm rounded-3xl bg-white hover:shadow-md transition-all duration-300 relative overflow-hidden">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100/60">
                System Configuration
            </span>
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mt-2">
                <i class="fas fa-sliders text-emerald-600"></i>
                Pengaturan Sistem
            </h3>
        </div>
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20">
            <i class="fas fa-gear"></i>
        </div>
    </div>

    {{-- Form Pengaturan --}}
    <form id="formPengaturan" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="tds_min" class="block text-[11px] font-bold uppercase text-slate-600 mb-1.5">
                    Batas TDS Minimum <span class="text-slate-400 font-normal lowercase">(ppm)</span>
                </label>
                <div class="relative">
                    <input type="number" id="tds_min" name="tds_min" placeholder="600" min="0" required
                           class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />
                    <span class="absolute right-3.5 top-2.5 text-xs font-black text-slate-400">PPM</span>
                </div>
            </div>

            <div>
                <label for="air_min" class="block text-[11px] font-bold uppercase text-slate-600 mb-1.5">
                    Batas Air Minimum <span class="text-slate-400 font-normal lowercase">(cm)</span>
                </label>
                <div class="relative">
                    <input type="number" id="air_min" name="air_min" placeholder="10" min="0" required
                           class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />
                    <span class="absolute right-3.5 top-2.5 text-xs font-black text-slate-400">CM</span>
                </div>
            </div>

            <div>
                <label for="interval" class="block text-[11px] font-bold uppercase text-slate-600 mb-1.5">
                    Interval Update <span class="text-slate-400 font-normal lowercase">(detik)</span>
                </label>
                <div class="relative">
                    <input type="number" id="interval" name="interval" placeholder="10" min="1" required
                           class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />
                    <span class="absolute right-3.5 top-2.5 text-xs font-black text-slate-400">DETIK</span>
                </div>
            </div>
        </div>

        <div>
            <label for="tanaman_aktif" class="block text-[11px] font-bold uppercase text-slate-600 mb-1.5">
                Tanaman Aktif Saat Ini
            </label>
            <select id="tanaman_aktif" name="tanaman_aktif"
                    class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                <option value="">Memuat daftar tanaman...</option>
            </select>
        </div>

        <button type="submit" id="btnSimpanPengaturan"
                class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-black py-3 px-4 rounded-xl shadow-md hover:shadow-emerald-500/20 transition-all duration-200 flex items-center justify-center gap-2 text-xs uppercase tracking-wider mt-3">
            <i class="fas fa-floppy-disk"></i>
            <span>Simpan Konfigurasi Sistem</span>
        </button>
    </form>

    {{-- LOCK OVERLAY (Tampil jika belum login) --}}
    @guest
    <div id="formLockOverlay" 
         class="absolute inset-0 bg-slate-950/75 backdrop-blur-md flex flex-col items-center justify-center text-white p-6 rounded-3xl cursor-pointer hover:bg-slate-950/80 transition-all group z-20">
        <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
            <i class="fas fa-lock text-2xl text-emerald-400 lock-icon"></i>
        </div>
        <p class="text-base font-black text-white text-center">Akses Pengaturan Terkunci</p>
        <p class="text-xs text-slate-300 text-center mt-1">Klik di sini untuk login sebagai admin</p>
    </div>
    @endguest
</div>

@push('scripts')
<script>
(function () {
    const formPengaturan = document.getElementById('formPengaturan');
    const selectTanaman = document.getElementById('tanaman_aktif');
    const lockOverlay = document.getElementById('formLockOverlay');

    const showAlert = (title, text, icon) => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title, text, icon, confirmButtonColor: '#059669' });
        } else {
            alert(`${title}: ${text}`);
        }
    };

    async function loadTanamanList() {
        if (!selectTanaman) return;
        try {
            const res = await fetch('{{ url('/api/tanaman') }}');
            if (!res.ok) {
                throw new Error(`Endpoint daftar tanaman merespons ${res.status}`);
            }
            const data = await res.json();

            selectTanaman.innerHTML = '<option value="">-- Pilih Tanaman --</option>';
            const list = Array.isArray(data) ? data : data.data;

            if (!Array.isArray(list) || list.length === 0) {
                selectTanaman.innerHTML = '<option value="">Belum ada data tanaman</option>';
                return;
            }

            list.forEach(t => {
                if (!t.id || !t.nama_tanaman) return;
                const opt = document.createElement('option');
                opt.value = t.id;
                opt.textContent = t.nama_tanaman;
                selectTanaman.appendChild(opt);
            });
        } catch (error) {
            console.error("Gagal memuat tanaman:", error);
            selectTanaman.innerHTML = '<option value="">Gagal memuat daftar tanaman</option>';
        }
    }

    async function fetchPengaturan() {
        try {
            const res = await fetch('{{ url('/api/batas') }}');
            if (!res.ok) return;
            const data = await res.json();

            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el && val !== undefined && val !== null) el.value = val;
            };

            setVal('tds_min', data.tds_min);
            setVal('air_min', data.air_min);
            setVal('interval', data.interval);

            if (selectTanaman && data.tanaman_aktif) {
                selectTanaman.value = data.tanaman_aktif;
            }
        } catch (error) {
            console.debug("Gagal memuat pengaturan:", error);
        }
    }

    if (formPengaturan) {
        formPengaturan.addEventListener('submit', async function (e) {
            e.preventDefault();

            const payload = {
                tds_min: document.getElementById('tds_min')?.value || '',
                air_min: document.getElementById('air_min')?.value || '',
                interval: document.getElementById('interval')?.value || '',
                tanaman_aktif: selectTanaman?.value || ''
            };

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                                || document.querySelector('input[name="_token"]')?.value || '';

                const res = await fetch('{{ url('/api/update-batas') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                const result = await res.json();

                if (res.ok && (result.success || result.status === 'success' || result.message)) {
                    showAlert('Berhasil!', result.message || 'Pengaturan berhasil disimpan.', 'success');
                    fetchPengaturan();
                    window.dispatchEvent(new Event('tanaman-aktif-updated'));
                } else {
                    showAlert('Gagal!', result.message || 'Terjadi kesalahan saat menyimpan.', 'error');
                }
            } catch (error) {
                console.error("Error simpan pengaturan:", error);
                showAlert('Gagal!', 'Terjadi kesalahan jaringan.', 'error');
            }
        });
    }

    if (lockOverlay) {
        lockOverlay.addEventListener('click', function () {
            this.outerHTML = `
                <div class="absolute inset-0 bg-white/95 backdrop-blur-md flex flex-col items-center justify-center p-6 rounded-3xl z-30 animate__animated animate__fadeIn">
                    <div class="w-full max-w-xs space-y-4 text-center">
                        <div>
                            <h4 class="text-base font-black text-slate-800">Login Administrator</h4>
                            <p class="text-xs text-slate-500">Masukkan kredensial untuk membuka pengaturan</p>
                        </div>
                        <form id="quickLoginForm" class="space-y-3 text-left">
                            <div>
                                <input id="quickUsername" type="text" placeholder="Username" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:border-emerald-500 focus:bg-white" />
                            </div>
                            <div>
                                <input id="quickPassword" type="password" placeholder="Password" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:border-emerald-500 focus:bg-white" />
                            </div>
                            <button type="submit" 
                                    class="w-full bg-emerald-600 text-white font-bold py-2.5 rounded-xl shadow-md hover:bg-emerald-700 transition-colors text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                                <i class="fas fa-key text-xs"></i> Unlocking Settings
                            </button>
                        </form>
                    </div>
                </div>
            `;

            setTimeout(() => {
                const quickLoginForm = document.getElementById("quickLoginForm");
                if (quickLoginForm) {
                    quickLoginForm.addEventListener("submit", async function (e) {
                        e.preventDefault();

                        const username = document.getElementById("quickUsername")?.value.trim();
                        const password = document.getElementById("quickPassword")?.value.trim();

                        if (!username || !password) return;

                        try {
                            const res = await fetch("{{ url('/api/login') }}", {
                                method: "POST",
                                headers: { 
                                    "Content-Type": "application/json",
                                    "Accept": "application/json"
                                },
                                body: JSON.stringify({ username, password })
                            });

                            const data = await res.json();

                            if (res.ok && (data.success || data.token)) {
                                showAlert("Autentikasi Berhasil!", "Akses pengaturan telah dibuka.", "success");
                                this.closest('.absolute').remove();
                            } else {
                                showAlert("Login Gagal", data.message || "Username atau password salah.", "error");
                            }
                        } catch (err) {
                            console.error(err);
                            showAlert("Gagal", "Terjadi kesalahan jaringan.", "error");
                        }
                    });
                }
            }, 50);
        });
    }

    const initializePengaturan = async () => {
        await loadTanamanList();
        await fetchPengaturan();
    };

    window.addEventListener('tanaman-list-updated', initializePengaturan);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePengaturan, { once: true });
    } else {
        initializePengaturan();
    }
})();
</script>
@endpush