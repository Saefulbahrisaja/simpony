<section class="glass-card rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
    <div class="mb-4 flex items-center justify-between gap-3 border-b border-slate-100 pb-3">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Data Tanaman</span>
            <h2 class="mt-0.5 flex items-center gap-2 text-lg font-extrabold text-slate-800">
                <i class="fas fa-seedling text-emerald-600"></i> Manajemen Tanaman
                <span id="tanamanCount" class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">…</span>
            </h2>
        </div>
        <div class="flex shrink-0 gap-2">
            <button id="btnMasukTanaman" type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                <i class="fas fa-lock"></i><span>Login</span>
            </button>
            <button id="btnKeluarTanaman" type="button" hidden class="hidden inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50">
                <i class="fas fa-right-from-bracket"></i><span>Keluar</span>
            </button>
            <button id="btnTambahTanaman" type="button" hidden class="hidden inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700">
            <i class="fas fa-plus"></i><span>Tambah</span>
            </button>
        </div>
    </div>

    <form id="formLoginTanaman" hidden class="hidden mb-4 rounded-xl border border-slate-200 bg-slate-50 p-3 sm:p-4">
        <h3 class="mb-3 text-sm font-bold text-slate-700">Login pengelola tanaman</h3>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <label for="usernameTanaman" class="mb-1 block text-[11px] font-semibold text-slate-600">Username</label>
                <input id="usernameTanaman" name="username" type="text" autocomplete="username" required
                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
            <div>
                <label for="passwordTanaman" class="mb-1 block text-[11px] font-semibold text-slate-600">Password</label>
                <input id="passwordTanaman" name="password" type="password" autocomplete="current-password" required
                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
        </div>
        <div class="mt-3 flex justify-end gap-2">
            <button id="btnBatalLoginTanaman" type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
            <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-emerald-700">Masuk</button>
        </div>
    </form>

    <form id="formTanaman" hidden class="hidden mb-4 rounded-xl border border-emerald-100 bg-emerald-50/40 p-3 sm:p-4">
        <h3 id="formTanamanTitle" class="mb-3 text-sm font-bold text-slate-700">Tambah tanaman</h3>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label for="nama_tanaman" class="mb-1 block text-[11px] font-semibold text-slate-600">Nama tanaman</label>
                <input id="nama_tanaman" name="nama_tanaman" type="text" maxlength="255" required
                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
            <div class="lg:col-span-2">
                <label for="nama_ilmiah" class="mb-1 block text-[11px] font-semibold text-slate-600">Nama ilmiah <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="nama_ilmiah" name="nama_ilmiah" type="text" maxlength="255"
                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
            <div>
                <label for="status_tanaman" class="mb-1 block text-[11px] font-semibold text-slate-600">Status</label>
                <select id="status_tanaman" name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="nonaktif">Nonaktif</option>
                    <option value="aktif">Aktif</option>
                </select>
            </div>
            <div>
                <label for="hst" class="mb-1 block text-[11px] font-semibold text-slate-600">Tanggal semai</label>
                <input id="hst" name="hst" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
            <div>
                <label for="hss" class="mb-1 block text-[11px] font-semibold text-slate-600">Tanggal tanam</label>
                <input id="hss" name="hss" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
        </div>
        <div class="mt-3 flex justify-end gap-2">
            <button id="btnBatalEditTanaman" type="button" hidden class="hidden rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
            <button id="btnSimpanTanaman" type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-emerald-700">
                <i class="fas fa-plus"></i><span>Simpan</span>
            </button>
        </div>
    </form>

    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-3 py-2.5 font-bold">Tanaman</th>
                    <th class="hidden px-3 py-2.5 font-bold sm:table-cell">Tanggal semai / tanam</th>
                    <th class="px-3 py-2.5 font-bold">Status</th>
                    <th class="px-3 py-2.5 text-right font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody id="daftarTanaman" class="divide-y divide-slate-100 bg-white">
                <tr><td colspan="4" class="px-3 py-5 text-center text-xs text-slate-400">Memuat daftar tanaman...</td></tr>
            </tbody>
        </table>
    </div>
    <p class="mt-2 text-[10px] text-slate-400">Login dibutuhkan untuk tambah, ubah, dan hapus. Tanaman dengan riwayat sensor tidak dapat dihapus.</p>
</section>

@push('scripts')
<script>
(function () {
    const endpoint = @json(url('/api/tanaman'));
    const loginEndpoint = @json(url('/api/login'));
    const logoutEndpoint = @json(url('/api/logout'));
    const form = document.getElementById('formTanaman');
    const loginForm = document.getElementById('formLoginTanaman');
    const tbody = document.getElementById('daftarTanaman');
    const count = document.getElementById('tanamanCount');
    const title = document.getElementById('formTanamanTitle');
    const saveButton = document.getElementById('btnSimpanTanaman');
    const cancelButton = document.getElementById('btnBatalEditTanaman');
    const addButton = document.getElementById('btnTambahTanaman');
    const loginButton = document.getElementById('btnMasukTanaman');
    const logoutButton = document.getElementById('btnKeluarTanaman');
    const cancelLoginButton = document.getElementById('btnBatalLoginTanaman');
    const tokenKey = 'simponi_plant_management_token';
    let tanamanList = [];
    let editingId = null;
    let pendingAction = null;

    const setHidden = (element, hidden) => {
        element.hidden = hidden;
        element.classList.toggle('hidden', hidden);
    };
    const getToken = () => sessionStorage.getItem(tokenKey);
    const setAuthUI = () => {
        const authenticated = Boolean(getToken());
        setHidden(loginButton, authenticated);
        setHidden(logoutButton, !authenticated);
        setHidden(addButton, !authenticated);
    };

    function openLogin(action = null) {
        pendingAction = action;
        setHidden(loginForm, false);
        loginForm.elements.username.focus();
    }

    function requireLogin(action) {
        if (getToken()) {
            action();
            return;
        }
        openLogin(action);
    }

    const notify = (message, type = 'success') => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: type === 'success' ? 'Berhasil' : 'Gagal', text: message, icon: type, confirmButtonColor: '#059669' });
        } else {
            alert(message);
        }
    };

    async function readResponse(response) {
        const body = await response.json().catch(() => ({}));
        if (!response.ok) {
            const validationMessage = body.errors ? Object.values(body.errors).flat()[0] : null;
            const error = new Error(validationMessage || body.message || `Permintaan gagal (${response.status})`);
            error.status = response.status;
            throw error;
        }
        return body;
    }

    function handleAuthError(error) {
        if (error.status === 401 || error.status === 403) {
            sessionStorage.removeItem(tokenKey);
            setAuthUI();
            return 'Sesi login berakhir. Silakan login kembali untuk mengelola tanaman.';
        }
        return error.message;
    }

    function renderRows() {
        count.textContent = `${tanamanList.length} tanaman`;
        tbody.replaceChildren();

        if (!tanamanList.length) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 4;
            cell.className = 'px-4 py-8 text-center text-sm text-slate-400';
            cell.textContent = 'Belum ada data tanaman.';
            row.appendChild(cell);
            tbody.appendChild(row);
            return;
        }

        tanamanList.forEach(tanaman => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-slate-50/70';

            const nameCell = document.createElement('td');
            nameCell.className = 'px-3 py-2.5';
            const name = document.createElement('div');
            name.className = 'font-semibold text-slate-800';
            name.textContent = tanaman.nama_tanaman;
            nameCell.appendChild(name);
            if (tanaman.nama_ilmiah) {
                const scientific = document.createElement('div');
                scientific.className = 'mt-0.5 text-xs italic text-slate-500';
                scientific.textContent = tanaman.nama_ilmiah;
                nameCell.appendChild(scientific);
            }
            const mobileDates = document.createElement('div');
            mobileDates.className = 'mt-1 text-[10px] text-slate-400 sm:hidden';
            mobileDates.textContent = `Semai ${tanaman.hst || '-'} · Tanam ${tanaman.hss || '-'}`;
            nameCell.appendChild(mobileDates);

            const dateCell = document.createElement('td');
            dateCell.className = 'hidden whitespace-nowrap px-3 py-2.5 text-xs text-slate-600 sm:table-cell';
            dateCell.textContent = `Semai: ${tanaman.hst || '-'} · Tanam: ${tanaman.hss || '-'}`;

            const statusCell = document.createElement('td');
            statusCell.className = 'px-3 py-2.5';
            const badge = document.createElement('span');
            const active = tanaman.status === 'aktif';
            badge.className = `rounded-full px-2.5 py-1 text-xs font-bold ${active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'}`;
            badge.textContent = active ? 'Aktif' : 'Nonaktif';
            statusCell.appendChild(badge);

            const actionCell = document.createElement('td');
            actionCell.className = 'whitespace-nowrap px-3 py-2.5 text-right';
            const edit = document.createElement('button');
            edit.type = 'button';
            edit.className = 'mr-2 rounded-lg border border-emerald-200 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50';
            edit.textContent = 'Edit';
            edit.addEventListener('click', () => requireLogin(() => beginEdit(tanaman)));
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'rounded-lg border border-rose-200 px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50';
            remove.textContent = 'Hapus';
            remove.addEventListener('click', () => requireLogin(() => deleteTanaman(tanaman)));
            actionCell.append(edit, remove);

            row.append(nameCell, dateCell, statusCell, actionCell);
            tbody.appendChild(row);
        });
    }

    async function loadTanaman() {
        try {
            const result = await readResponse(await fetch(endpoint, { headers: { Accept: 'application/json' }, cache: 'no-store' }));
            tanamanList = Array.isArray(result.data) ? result.data : [];
            renderRows();
        } catch (error) {
            count.textContent = 'Gagal memuat';
            tbody.innerHTML = '';
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 4;
            cell.className = 'px-4 py-8 text-center text-sm text-rose-600';
            cell.textContent = error.message;
            row.appendChild(cell);
            tbody.appendChild(row);
        }
    }

    function resetForm() {
        form.reset();
        editingId = null;
        setHidden(form, true);
        title.textContent = 'Tambah tanaman';
        saveButton.querySelector('span').textContent = 'Simpan';
        saveButton.querySelector('i').className = 'fas fa-plus';
        setHidden(cancelButton, true);
    }

    function beginEdit(tanaman) {
        editingId = tanaman.id;
        setHidden(form, false);
        form.elements.nama_tanaman.value = tanaman.nama_tanaman || '';
        form.elements.nama_ilmiah.value = tanaman.nama_ilmiah || '';
        form.elements.hst.value = tanaman.hst || '';
        form.elements.hss.value = tanaman.hss || '';
        form.elements.status.value = tanaman.status || 'nonaktif';
        title.textContent = `Ubah tanaman #${tanaman.id}`;
        saveButton.querySelector('span').textContent = 'Simpan Perubahan';
        saveButton.querySelector('i').className = 'fas fa-floppy-disk';
        setHidden(cancelButton, false);
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    addButton.addEventListener('click', () => requireLogin(() => {
        resetForm();
        setHidden(form, false);
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        form.elements.nama_tanaman.focus();
    }));

    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (!getToken()) {
            openLogin(() => form.requestSubmit());
            return;
        }

        const payload = Object.fromEntries(new FormData(form).entries());
        ['nama_ilmiah', 'hst', 'hss'].forEach(key => { if (!payload[key]) payload[key] = null; });

        try {
            const response = await fetch(editingId ? `${endpoint}/${editingId}` : endpoint, {
                method: editingId ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    Authorization: `Bearer ${getToken()}`,
                },
                body: JSON.stringify(payload),
            });
            const result = await readResponse(response);
            notify(result.message || 'Data tanaman tersimpan.');
            resetForm();
            await loadTanaman();
            window.dispatchEvent(new Event('tanaman-list-updated'));
        } catch (error) {
            notify(handleAuthError(error), 'error');
        }
    });

    async function deleteTanaman(tanaman) {
        let confirmed = false;
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'Hapus tanaman?',
                text: `Data ${tanaman.nama_tanaman} akan dihapus.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e11d48',
            });
            confirmed = result.isConfirmed;
        } else {
            confirmed = window.confirm(`Hapus tanaman ${tanaman.nama_tanaman}?`);
        }
        if (!confirmed) return;

        try {
            const result = await readResponse(await fetch(`${endpoint}/${tanaman.id}`, {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${getToken()}`,
                },
            }));
            if (editingId === tanaman.id) resetForm();
            notify(result.message || 'Tanaman berhasil dihapus.');
            await loadTanaman();
            window.dispatchEvent(new Event('tanaman-list-updated'));
        } catch (error) {
            notify(handleAuthError(error), 'error');
        }
    }

    loginButton.addEventListener('click', () => openLogin());
    cancelLoginButton.addEventListener('click', () => {
        pendingAction = null;
        loginForm.reset();
        setHidden(loginForm, true);
    });

    loginForm.addEventListener('submit', async event => {
        event.preventDefault();
        try {
            const result = await readResponse(await fetch(loginEndpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({
                    username: loginForm.elements.username.value,
                    password: loginForm.elements.password.value,
                }),
            }));

            if (!result.token) throw new Error('Server tidak mengirim token login.');
            sessionStorage.setItem(tokenKey, result.token);
            loginForm.reset();
            setHidden(loginForm, true);
            setAuthUI();
            const action = pendingAction;
            pendingAction = null;
            if (action) action();
            else notify(result.message || 'Login berhasil.');
        } catch (error) {
            notify(error.message, 'error');
        }
    });

    logoutButton.addEventListener('click', async () => {
        try {
            await fetch(logoutEndpoint, {
                method: 'POST',
                headers: { Accept: 'application/json', Authorization: `Bearer ${getToken()}` },
            });
        } finally {
            sessionStorage.removeItem(tokenKey);
            setAuthUI();
            resetForm();
        }
    });

    cancelButton.addEventListener('click', resetForm);
    setAuthUI();
    loadTanaman();
})();
</script>
@endpush
