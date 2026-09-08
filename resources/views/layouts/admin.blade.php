<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true }" x-init="() => { if(window.innerWidth < 768) sidebarOpen = false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) document.documentElement.classList.add('dark');
        }());
    </script>

    <title>Prestasimu | Admin | @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(148, 163, 184, 0.15); }
        ::-webkit-scrollbar-thumb { background: rgba(71, 85, 105, 0.6); border-radius: 999px; }

        .sidebar-transition {
            transition: transform 0.3s ease, width 0.3s ease, margin 0.3s ease;
        }

        .dark ::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.7); }
        .dark ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.7); }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    @stack('head-scripts')
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <div class="flex min-h-screen">
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-950/50 md:hidden" x-transition.opacity></div>

        <aside
            :class="sidebarOpen ? 'translate-x-0 md:w-72' : '-translate-x-full md:translate-x-0 md:w-16'"
            class="fixed inset-y-0 left-0 z-50 h-screen w-72 border-r border-slate-200 bg-white transition-all duration-200 dark:border-slate-800 dark:bg-slate-900"
        >
            <x-admin.sidebar />
        </aside>

        <div class="flex min-w-0 flex-1 flex-col transition-[margin] duration-200" :class="sidebarOpen ? 'md:ml-72' : 'md:ml-16'">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur md:px-8 dark:border-slate-800 dark:bg-slate-900/90">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 md:hidden dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Buka menu navigasi">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="min-w-0">
                        <p class="truncate text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Panel administrasi</p>
                        <p class="truncate text-sm font-semibold text-slate-700 dark:text-slate-200">@yield('title', 'Dashboard')</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 sm:gap-3">
                    <span class="hidden sm:inline">{{ now()->translatedFormat('d F Y') }}</span>
                    <a href="{{ route('home') }}" target="_blank" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-700 dark:hover:bg-slate-800" title="Buka portal publik" aria-label="Buka portal publik"><i class="fas fa-arrow-up-right-from-square"></i></a>
                    <a href="{{ route('profile.edit') }}" class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-700 transition hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300 dark:hover:bg-emerald-900/70" title="Buka profil" aria-label="Buka profil">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</a>
                </div>
            </header>
            <main class="mx-auto w-full max-w-[1600px] flex-1 p-4 md:p-5 lg:p-6">
                <div class="mb-6 space-y-3">
                    @if(session('success'))
                        <x-alert type="success" :message="session('success')" />
                    @endif

                    @if(session('error'))
                        <x-alert type="error" :message="session('error')" />
                    @endif

                    @if(session('warning'))
                        <x-alert type="warning" :message="session('warning')" />
                    @endif

                    @if(session('info'))
                        <x-alert type="info" :message="session('info')" />
                    @endif
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <div id="ajax-feedback" class="pointer-events-none fixed right-4 top-20 z-[80] hidden max-w-sm rounded-xl px-4 py-3 text-sm font-semibold shadow-lg" role="status" aria-live="polite"></div>

    <script>
        (() => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const feedback = document.getElementById('ajax-feedback');

            function notify(message, type = 'success') {
                if (!feedback) return;
                feedback.textContent = message;
                feedback.className = `pointer-events-auto fixed right-4 top-20 z-[80] max-w-sm rounded-xl px-4 py-3 text-sm font-semibold shadow-lg ${type === 'error' ? 'bg-red-600 text-white' : 'bg-emerald-600 text-white'}`;
                window.setTimeout(() => feedback.classList.add('hidden'), 4500);
            }

            window.adminNotify = notify;

            document.addEventListener('submit', async (event) => {
                const form = event.target.closest('form[data-ajax-form]');
                if (!form) return;
                event.preventDefault();
                const submit = form.querySelector('[type="submit"]');
                if (submit) submit.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: (form.method || 'POST').toUpperCase(),
                        body: new FormData(form),
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
                        credentials: 'same-origin',
                    });
                    const data = await response.json();
                    if (!response.ok) {
                        const messages = Object.values(data.errors || {}).flat();
                        notify(messages.join(' ') || data.message || 'Data belum dapat disimpan.', 'error');
                        return;
                    }
                    notify(data.message || 'Data berhasil disimpan.');
                    if (data.redirect) window.location.assign(data.redirect);
                } catch (error) {
                    notify('Terjadi gangguan jaringan. Silakan coba lagi.', 'error');
                } finally {
                    if (submit) submit.disabled = false;
                }
            });

            async function loadPage(link) {
                const response = await fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                if (!response.ok) throw new Error('Page request failed');
                const html = await response.text();
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const incoming = parsed.querySelector('main');
                const current = document.querySelector('main');
                if (!incoming || !current) return window.location.assign(link.href);
                current.innerHTML = incoming.innerHTML;
                document.title = parsed.title;
                window.history.pushState({}, '', link.href);
                current.querySelectorAll('script').forEach((script) => {
                    const replacement = document.createElement('script');
                    replacement.textContent = script.textContent;
                    document.body.appendChild(replacement);
                    replacement.remove();
                });
                if (window.Alpine) window.Alpine.initTree(current);
            }

            document.addEventListener('click', async (event) => {
                const link = event.target.closest('a[data-ajax-page]');
                if (!link || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
                event.preventDefault();
                try { await loadPage(link); } catch (error) { window.location.assign(link.href); }
            });

            window.addEventListener('popstate', () => window.location.reload());
        })();
    </script>

    @stack('scripts')
</body>
</html>