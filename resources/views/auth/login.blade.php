<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-6 text-slate-800 sm:px-8 sm:py-10 dark:bg-slate-950">
        <div class="mx-auto grid w-full max-w-5xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_24px_70px_rgba(15,23,42,0.12)] dark:border-slate-800 dark:bg-slate-900 lg:grid-cols-[1fr_0.9fr]">
            <section class="flex min-w-0 items-center px-5 py-8 sm:px-10 sm:py-12 lg:px-12 xl:px-16">
                <div class="mx-auto w-full max-w-md">
                    <!-- Header -->
                    <a href="{{ route('home') }}" class="mb-8 inline-flex min-w-0 items-center gap-3 transition-opacity hover:opacity-80">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 p-1.5 dark:bg-emerald-900/30">
                            <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK N 1 Bangsri" class="h-full w-full object-contain">
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">SMK Negeri 1 Bangsri</span>
                    </a>

                    <div class="mb-8">
                        <div class="inline-flex items-center gap-2 rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 dark:border-emerald-800 dark:bg-emerald-950/50">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-emerald-800 dark:text-emerald-300">Portal Administrator</span>
                        </div>
                        <h1 class="mt-5 text-3xl font-bold leading-tight text-slate-900 dark:text-white sm:text-[2rem]">Masuk ke akun Anda</h1>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Kelola data prestasi siswa melalui panel administrasi sekolah.</p>
                    </div>

                    <!-- Alerts -->
                    @if(session('status'))
                        <div class="mb-6 animate-fade-in rounded-xl border border-green-200 bg-green-50 p-4">
                            <div class="flex gap-3">
                                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-semibold text-green-900">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 animate-fade-in rounded-xl border border-green-200 bg-green-50 p-4">
                            <div class="flex gap-3">
                                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-semibold text-green-900">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 animate-fade-in rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="flex gap-3">
                                <i class="fas fa-exclamation-circle text-red-600 mt-0.5 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-red-900">Autentikasi Gagal</p>
                                    <p class="mt-1 text-xs text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 animate-fade-in rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="flex gap-3">
                                <i class="fas fa-exclamation-circle text-red-600 mt-0.5 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-red-900">Login Gagal</p>
                                    <p class="mt-1 text-xs text-red-700">{{ $errors->first() }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm">
                        @csrf

                        <!-- Email Input -->
                        <div class="space-y-2.5">
                            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Email</label>
                            <div class="group relative">
                                <div class="absolute left-0 top-0 h-full w-12 flex items-center justify-center text-emerald-600 group-focus-within:text-emerald-700 transition">
                                    <i class="fas fa-envelope text-sm"></i>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                    class="w-full rounded-lg border border-slate-300 bg-white py-3.5 pl-12 pr-4 text-sm font-medium text-slate-900 transition
                                    placeholder:text-slate-400 placeholder:font-normal
                                    focus:border-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-700/10
                                    hover:border-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-white
                                    @error('email') border-red-500 bg-red-50/30 dark:bg-red-950/20 @enderror"
                                >
                                @error('email')
                                    <div class="absolute right-0 top-0 h-full flex items-center justify-center text-red-500 pr-4">
                                        <i class="fas fa-exclamation-circle text-sm"></i>
                                    </div>
                                @enderror
                            </div>
                            @error('email')
                                <p class="mt-2 text-xs font-medium text-red-600 flex items-center gap-1.5">
                                    <i class="fas fa-circle-info"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-2.5">
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Kata sandi</label>
                            <div class="group relative">
                                <div class="absolute left-0 top-0 h-full w-12 flex items-center justify-center text-emerald-600 group-focus-within:text-emerald-700 transition">
                                    <i class="fas fa-lock text-sm"></i>
                                </div>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan kata sandi"
                                    class="w-full rounded-lg border border-slate-300 bg-white py-3.5 pl-12 pr-12 text-sm font-medium text-slate-900 transition
                                    placeholder:text-slate-400 placeholder:font-normal
                                    focus:border-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-700/10
                                    hover:border-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-white
                                    @error('password') border-red-500 bg-red-50/30 dark:bg-red-950/20 @enderror"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    aria-label="Tampilkan/Sembunyikan password"
                                    class="absolute right-0 top-0 flex h-full w-12 items-center justify-center rounded-r-lg text-slate-400 transition hover:text-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-emerald-600"
                                >
                                    <i id="eyeIcon" class="fas fa-eye text-sm"></i>
                                </button>
                                @error('password')
                                    <div class="absolute right-12 top-0 h-full flex items-center justify-center text-red-500">
                                        <i class="fas fa-exclamation-circle text-sm"></i>
                                    </div>
                                @enderror
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs font-medium text-red-600 flex items-center gap-1.5">
                                    <i class="fas fa-circle-info"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-3 pt-1">
                            <label class="group flex cursor-pointer items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="h-4 w-4 cursor-pointer rounded border-slate-300 text-emerald-700 transition focus:ring-emerald-600 dark:border-slate-600 dark:bg-slate-950"
                                >
                                <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 dark:text-slate-400 dark:group-hover:text-white">Ingat saya</span>
                            </label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            id="submitBtn"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-800 px-4 py-3.5 text-sm font-bold text-white shadow-sm shadow-emerald-950/20 transition duration-200
                            hover:bg-emerald-900 hover:shadow-md
                            active:scale-[0.99]
                            disabled:opacity-75 disabled:cursor-not-allowed disabled:shadow-none
                            focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-emerald-700/20"
                        >
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            <span id="btnText">Masuk ke Dashboard</span>
                            <i id="btnLoader" class="fas fa-spinner hidden animate-spin"></i>
                        </button>

                    </form>

                    <!-- Footer -->
                    <div class="mt-8 flex flex-col gap-2 border-t border-slate-100 pt-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800 dark:text-slate-400">
                        <span>&copy; {{ date('Y') }} SMK Negeri 1 Bangsri</span>
                        <a href="{{ route('home') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300">
                            Kembali ke beranda
                        </a>
                    </div>
                </div>
            </section>

            <section class="relative hidden min-w-0 flex-col justify-between overflow-hidden bg-emerald-950 p-10 text-white lg:flex xl:p-12">
                <div class="absolute inset-0 bg-[linear-gradient(145deg,rgba(16,107,75,0.9),rgba(6,50,43,1)_72%)]"></div>
                <div class="relative z-10 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-white/15 bg-white/10 p-2">
                        <img src="{{ asset('images/logo-smk.png') }}" alt="" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-bold">SMK Negeri 1 Bangsri</p>
                        <p class="mt-0.5 text-xs text-emerald-100/75">Sistem Informasi Prestasi Siswa</p>
                    </div>
                </div>

                <div class="relative z-10 my-12 max-w-md">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl border border-emerald-300/20 bg-emerald-300/10 text-emerald-200">
                        <i class="fas fa-award text-xl" aria-hidden="true"></i>
                    </div>
                    <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-200">Portal Pengelolaan Admin</p>
                    <h2 class="text-3xl font-bold leading-tight xl:text-4xl">Setiap pencapaian layak tercatat.</h2>
                    <p class="mt-4 text-sm leading-6 text-emerald-50/80">Kelola data siswa, prestasi, dan publikasi sekolah dari satu ruang kerja yang tertata.</p>
                </div>

                <div class="relative z-10 flex items-center gap-3 border-t border-white/10 pt-5 text-xs text-emerald-100/80">
                    <i class="fas fa-shield-halved text-emerald-300" aria-hidden="true"></i>
                    <span>Akses khusus administrator sekolah</span>
                </div>
            </section>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            password.type = password.type === 'password' ? 'text' : 'password';
            icon.className = password.type === 'password' ? 'fas fa-eye text-sm' : 'fas fa-eye-slash text-sm';
        }

        // Handle loading state properly on form submit
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                const btnText = document.getElementById('btnText');
                const btnLoader = document.getElementById('btnLoader');

                if (btn) {
                    btn.disabled = true;
                }
                if (btnText) {
                    btnText.classList.add('hidden');
                }
                if (btnLoader) {
                    btnLoader.classList.remove('hidden');
                }
            });
        }

        // Add smooth focus effects
        document.querySelectorAll('input[type="email"], input[type="password"]').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.closest('.group')?.classList.add('ring-2', 'ring-emerald-300', 'ring-opacity-50');
            });
            input.addEventListener('blur', function() {
                this.parentElement.closest('.group')?.classList.remove('ring-2', 'ring-emerald-300', 'ring-opacity-50');
            });
        });
    </script>
</x-guest-layout>
