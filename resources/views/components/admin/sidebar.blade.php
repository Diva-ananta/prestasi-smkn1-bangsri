<aside class="flex h-full flex-col overflow-y-auto">
    <div class="border-b border-slate-200 px-3 py-5 transition-all dark:border-slate-700" :class="sidebarOpen ? 'md:px-5' : 'md:px-2'">
        <div class="flex items-center gap-3" :class="sidebarOpen ? '' : 'md:justify-center'">
            <button type="button" @click="sidebarOpen = !sidebarOpen" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-800 text-xl text-white shadow-lg shadow-emerald-700/20 transition hover:bg-emerald-900" title="Buka atau sembunyikan sidebar" aria-label="Buka atau sembunyikan sidebar">
                <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK" class="h-9 w-9 object-contain">
            </button>
            <div x-show="sidebarOpen" x-transition.opacity>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h1 class="text-lg font-bold text-slate-800 dark:text-white">SIPRES</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">SMK N 1 Bangsri</p>
                    </div>
                    <button type="button" @click="sidebarOpen = false" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-700 dark:hover:bg-slate-800 dark:hover:text-emerald-300" title="Sembunyikan sidebar" aria-label="Sembunyikan sidebar">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 space-y-2 p-4">
        <div x-show="sidebarOpen" class="mb-3 px-2 text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Menu utama</div>

        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-slate-700 dark:text-slate-300' }}" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Dashboard">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span x-show="sidebarOpen" x-transition.opacity>Dashboard</span>
        </a>

        <a href="{{ route('admin.siswa.index') }}" class="sidebar-link {{ request()->routeIs('admin.siswa.*') ? 'active' : 'text-slate-700 dark:text-slate-300' }}" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Siswa">
            <i class="fas fa-users w-5"></i>
            <span x-show="sidebarOpen" x-transition.opacity>Siswa</span>
        </a>

        <a href="{{ route('admin.prestasi.index') }}" class="sidebar-link {{ request()->routeIs('admin.prestasi.*') ? 'active' : 'text-slate-700 dark:text-slate-300' }}" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Prestasi">
            <i class="fas fa-medal w-5"></i>
            <span x-show="sidebarOpen" x-transition.opacity>Prestasi</span>
        </a>

        <a href="{{ route('admin.artikel.index') }}" class="sidebar-link {{ request()->routeIs('admin.artikel.*') ? 'active' : 'text-slate-700 dark:text-slate-300' }}" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Artikel">
            <i class="fas fa-newspaper w-5"></i>
            <span x-show="sidebarOpen" x-transition.opacity>Artikel</span>
        </a>

        <div x-show="sidebarOpen" class="mb-1 mt-5 px-2 text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Peralatan</div>

        <a href="{{ route('admin.galeri.index') }}" class="sidebar-link {{ request()->routeIs('admin.galeri.*') ? 'active' : 'text-slate-700 dark:text-slate-300' }}" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Galeri">
            <i class="fas fa-images w-5"></i>
            <span x-show="sidebarOpen" x-transition.opacity>Galeri</span>
        </a>
        <a href="{{ route('admin.sipintu.index') }}" class="sidebar-link {{ request()->routeIs('admin.sipintu.*') ? 'active' : 'text-slate-700 dark:text-slate-300' }}" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="SiPintu Gateway">
            <i class="fas fa-door-open w-5 text-center text-emerald-600 dark:text-emerald-400"></i>
            <span x-show="sidebarOpen" x-transition.opacity class="flex items-center justify-between flex-1">
                <span>SiPintu Gateway</span>
                <span class="rounded-full bg-emerald-100 px-1.5 py-0.5 text-[9px] font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">API</span>
            </span>
        </a>
        <a href="{{ route('admin.deleted-records.index') }}" class="sidebar-link text-slate-700 dark:text-slate-300" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Riwayat data"><i class="fas fa-trash-restore w-5 text-center"></i><span x-show="sidebarOpen" x-transition.opacity>Riwayat Data</span></a>

        <a href="{{ route('profile.edit') }}" class="sidebar-link text-slate-700 dark:text-slate-300" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Profil">
            <i class="fas fa-user-cog w-5"></i>
            <span x-show="sidebarOpen" x-transition.opacity>Profil</span>
        </a>

        <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

        <form method="POST" action="{{ route('logout') }}" class="px-1">
            @csrf
            <button type="submit" class="sidebar-link w-full justify-start text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30" :class="sidebarOpen ? '' : 'md:justify-center md:px-0'" title="Logout">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span x-show="sidebarOpen" x-transition.opacity>Logout</span>
            </button>
        </form>
    </nav>

    <div class="border-t border-slate-200 p-3 dark:border-slate-700">
        <div class="mb-2 flex items-center gap-3 rounded-xl bg-slate-50 p-2 dark:bg-slate-800" :class="sidebarOpen ? '' : 'md:justify-center'">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="sidebarOpen" x-transition.opacity class="min-w-0">
                <p class="text-[10px] text-slate-400">Logged in</p>
                <p class="truncate text-sm font-semibold text-slate-700 dark:text-slate-200">{{ Auth::user()->name }}</p>
            </div>
        </div>
        <div class="flex items-center" :class="sidebarOpen ? 'justify-between px-2' : 'justify-center'">
            <span x-show="sidebarOpen" class="text-xs text-slate-400">Tampilan</span>
            <x-dark-mode-toggle />
        </div>
    </div>
</aside>