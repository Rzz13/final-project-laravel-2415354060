<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ERP System - Kelola pelanggan, layanan, dan langganan bisnis Anda">
    <title>ERP System – @yield('title', 'Dashboard')</title>

    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    {{-- Tailwind CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex h-screen overflow-hidden bg-gray-50 text-gray-900 font-sans">

    {{-- SIDEBAR --}}
    <aside class="flex flex-col w-48 min-w-[192px] h-screen bg-white border-r border-gray-200">

        {{-- Brand --}}
        <div class="flex items-center gap-2.5 px-5 py-[18px] border-b border-gray-200">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10">
            <button class="ml-auto text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded p-1 transition-colors"
                title="Toggle sidebar">
                <i class='bx bx-layout text-lg'></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5">

            {{-- Users --}}
            <a href="{{ route('web.users') }}" id="nav-users"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('web.users') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class='bx bx-user text-base'></i>
                Users
            </a>

            {{-- Customers --}}
            <a href="{{ route('web.customers') }}" id="nav-customers"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('web.customers') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class='bx bx-group text-base'></i>
                Customers
            </a>

            {{-- Services --}}
            <a href="{{ route('web.services') }}" id="nav-services"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('web.services') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class='bx bx-wrench text-base'></i>
                Services
            </a>

            {{-- Subscription --}}
            <a href="{{ route('web.subscriptions') }}" id="nav-subscriptions"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('web.subscriptions') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class='bx bx-file text-base'></i>
                Subscription
            </a>

        </nav>

        {{-- Footer – Sign Out --}}
        <div class="px-3 py-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" id="btn-signout"
                    class="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-gray-500
                               hover:bg-red-50 hover:text-red-600 transition-colors text-left">
                    <i class='bx bx-log-out text-base'></i>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex flex-col flex-1 overflow-hidden">

        {{-- Topbar --}}
        <header class="flex items-center h-[57px] px-7 bg-white border-b border-gray-200 shrink-0">
            <h1 class="text-[15px] font-semibold text-gray-900">@yield('page-title')</h1>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-7">
            @yield('content')
        </main>

    </div>

    {{-- Modal shell (template design) --}}
    @include('modal')

    @stack('scripts')

    <script>
        (() => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            /* ════════════════════════════════════════════
               DROPDOWN ACTION
            ════════════════════════════════════════════ */
            document.addEventListener('click', e => {
                const trigger = e.target.closest('.action-trigger');
                const allMenus = document.querySelectorAll('.action-menu:not(.hidden)');

                if (trigger) {
                    e.stopPropagation();
                    const menu = trigger.closest('td').querySelector('.action-menu');
                    allMenus.forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                    return;
                }

                if (!e.target.closest('.action-menu')) {
                    allMenus.forEach(m => m.classList.add('hidden'));
                }
            });

            document.addEventListener('click', async e => {
                const btn = e.target.closest('.dropdown-action');
                if (!btn) return;
                e.stopPropagation();

                const {
                    resource,
                    action,
                    id
                } = btn.dataset;
                btn.closest('.action-menu').classList.add('hidden');

                if (action === 'edit') {
                    document.dispatchEvent(new CustomEvent('erp:edit', {
                        detail: {
                            resource,
                            id
                        }
                    }));
                    return;
                }
                if (action === 'delete') {
                    if (!confirm('Hapus data ini?')) return;
                    await apiFetch(`/api/${resource}/${id}`, 'DELETE');
                    return;
                }
                if (action === 'activate') {
                    if (resource === "customers") {
                        await apiFetch(`/api/${resource}/${id}/change-status?activate=true`, 'PATCH');
                        return;
                    } else if (resource === "services") {
                        await apiFetch(`/api/${resource}/${id}/change-status?status=true`, 'PATCH');
                        return;
                    }
                }
                if (action === 'deactivate') {
                    if (resource === "customers") {
                        await apiFetch(`/api/${resource}/${id}/change-status?activate=false`, 'PATCH');
                        return;
                    } else if (resource === "services") {
                        await apiFetch(`/api/${resource}/${id}/change-status?status=false`, 'PATCH');
                        return;
                    }
                }
                if (action.startsWith('status:')) {
                    const status = action.split(':')[1];
                    await apiFetch(`/api/${resource}/${id}/status`, 'PATCH', {
                        status
                    });
                    return;
                }
            });

            // MODAL
            const modal = document.getElementById('erp-modal');
            const modalTitle = document.getElementById('erp-modal-title');
            const modalBody = document.getElementById('erp-modal-body');
            const modalCancel = document.getElementById('erp-modal-cancel');
            const modalSubmit = document.getElementById('erp-modal-submit');
            const backdrop = document.getElementById('erp-modal-backdrop');

            function openModal() {
                const tpl = document.getElementById('erp-modal-tpl');
                if (!tpl) return;

                modalTitle.textContent = tpl.dataset.title ?? '';
                modalBody.innerHTML = '';
                modalBody.appendChild(tpl.content.cloneNode(true));

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
                modalBody.innerHTML = '';
            }

            document.addEventListener('click', e => {
                if (e.target.closest('[data-open-modal]')) openModal();
            });

            modalCancel.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeModal();
            });

            modalSubmit.addEventListener('click', async () => {
                const form = modalBody.querySelector('form[data-endpoint]');
                if (!form) return;

                const endpoint = form.dataset.endpoint;
                const method = (form.dataset.method ?? 'POST').toUpperCase();

                const payload = {};
                new FormData(form).forEach((val, key) => {
                    payload[key] = val;
                });

                await apiFetch(endpoint, method, payload);
            });

            // Handler Edit
            document.addEventListener('erp:edit', async ({
                detail: {
                    resource,
                    id
                }
            }) => {
                const tpl = document.getElementById('erp-modal-tpl');
                if (!tpl) return;

                // Tampilkan modal dengan state loading
                modalTitle.textContent = 'Memuat data...';
                modalBody.innerHTML =
                    '<div class="flex justify-center py-10"><i class="bx bx-loader-alt bx-spin text-3xl text-gray-400"></i></div>';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');

                try {
                    const res = await fetch(`/api/${resource}/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                    });
                    if (!res.ok) throw new Error('Gagal memuat data');

                    const json = await res.json();
                    const data = json.data ?? json;

                    // Ganti judul Add → Edit
                    const originalTitle = tpl.dataset.title ?? '';
                    modalTitle.textContent = originalTitle.replace(/^Add\b/, 'Edit');

                    // Clone template dan ubah ke mode edit
                    modalBody.innerHTML = '';
                    const clone = tpl.content.cloneNode(true);
                    const form = clone.querySelector('form[data-endpoint]');
                    if (form) {
                        form.dataset.endpoint = `/api/${resource}/${id}`;
                        form.dataset.method = 'PUT';
                    }
                    modalBody.appendChild(clone);

                    // Pre-fill setiap field dengan data dari API
                    const formEl = modalBody.querySelector('form');
                    if (formEl && data) {
                        Object.entries(data).forEach(([key, value]) => {
                            const field = formEl.elements[key];
                            if (!field) return;

                            if (field.tagName === 'TEXTAREA') {
                                field.value = value ?? '';
                            } else if (field.type === 'date' && value) {
                                // Format tanggal ke YYYY-MM-DD
                                field.value = value.toString().substring(0, 10);
                            } else if (typeof value === 'boolean') {
                                field.value = value ? '1' : '0';
                            } else {
                                field.value = value ?? '';
                            }
                        });
                    }

                } catch (err) {
                    alert(err.message);
                    closeModal();
                }
            });

            // HELPER FETCH
            async function apiFetch(url, method, body = null) {
                try {
                    const opts = {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                    };
                    if (body) opts.body = JSON.stringify(body);

                    const res = await fetch(url, opts);
                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        throw new Error(err.message ?? 'Permintaan gagal');
                    }
                    window.location.reload();
                } catch (err) {
                    alert(err.message);
                }
            }
        })();
    </script>
</body>

</html>
