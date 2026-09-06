<x-guest-layout>
    <div class="flex min-h-screen items-center bg-gradient-to-br from-slate-50 via-emerald-50 to-slate-50 px-4 py-8 text-slate-800 sm:px-8 lg:px-12">
        <div class="mx-auto grid min-h-[calc(100vh-4rem)] w-full max-w-5xl overflow-hidden rounded-3xl border border-emerald-200/50 bg-white shadow-2xl lg:grid-cols-[0.9fr_1.1fr]">
            <!-- Left Panel - Login Form -->
            <section class="flex items-center px-6 py-10 sm:px-12 lg:px-16">
                <div class="mx-auto w-full max-w-sm">
                    <!-- Header -->
                    <a href="{{ route('home') }}" class="mb-12 inline-flex items-center gap-3 transition-transform hover:scale-105">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 p-1.5 flex items-center justify-center">
                            <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK N 1 Bangsri" class="h-full w-full object-contain">
                        </div>
                        <span class="text-sm font-bold bg-gradient-to-r from-emerald-700 to-emerald-900 bg-clip-text text-transparent">SMK Negeri 1 Bangsri</span>
                    </a>

                    <div class="mb-10">
                        <div class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-emerald-50 to-lime-50 px-3 py-1.5 border border-emerald-200/50">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700">Admin Dashboard</span>
                        </div>
                        <h1 class="mt-6 text-4xl font-bold tracking-tight text-slate-900">Selamat datang kembali</h1>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Masuk untuk mengelola data prestasi siswa dengan aman.</p>
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
                    <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                        @csrf

                        <!-- Email Input -->
                        <div class="space-y-2.5">
                            <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
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
                                    class="w-full rounded-xl border-2 border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm font-medium transition
                                    placeholder:text-slate-400 placeholder:font-normal
                                    focus:border-emerald-600 focus:bg-emerald-50/30 focus:outline-none focus:ring-0
                                    hover:border-slate-300
                                    @error('email') border-red-500 bg-red-50/30 @enderror"
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
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
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
                                    placeholder="Masukkan password Anda"
                                    class="w-full rounded-xl border-2 border-slate-200 bg-white py-3.5 pl-12 pr-12 text-sm font-medium transition
                                    placeholder:text-slate-400 placeholder:font-normal
                                    focus:border-emerald-600 focus:bg-emerald-50/30 focus:outline-none focus:ring-0
                                    hover:border-slate-300
                                    @error('password') border-red-500 bg-red-50/30 @enderror"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    aria-label="Tampilkan/Sembunyikan password"
                                    class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-slate-400 transition hover:text-emerald-700 focus:outline-none"
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
                        <div class="flex items-center justify-between gap-4 pt-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="h-5 w-5 rounded border-slate-300 text-emerald-700 transition focus:ring-emerald-600 cursor-pointer"
                                >
                                <span class="text-sm text-slate-600 font-medium group-hover:text-slate-900">Ingat saya</span>
                            </label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-900 transition">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            id="submitBtn"
                            onclick="handleSubmit()"
                            class="w-full rounded-xl bg-gradient-to-r from-emerald-700 to-emerald-800 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-900/20 transition duration-200
                            hover:from-emerald-800 hover:to-emerald-900 hover:shadow-xl hover:shadow-emerald-900/30
                            active:scale-[0.98]
                            disabled:opacity-75 disabled:cursor-not-allowed disabled:shadow-none
                            flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            <span id="btnText">Masuk ke Dashboard</span>
                            <i id="btnLoader" class="fas fa-spinner hidden animate-spin"></i>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="mt-12 flex items-center justify-between gap-4 text-xs text-slate-500">
                        <span>&copy; {{ date('Y') }} SMK Negeri 1 Bangsri</span>
                        <a href="{{ route('home') }}" class="font-semibold text-emerald-700 hover:text-emerald-900 transition">
                            Kembali ke portal
                        </a>
                    </div>
                </div>
            </section>

            <!-- Right Panel - Illustration -->
            <section class="relative hidden overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-800 to-emerald-900 lg:flex flex-col justify-between p-12 text-white xl:p-16">
                <!-- Decorative Elements -->
                <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full border-[42px] border-lime-300/20 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full border-[54px] border-white/5 blur-3xl"></div>
                <div class="absolute right-12 top-16 h-4 w-4 rounded-full bg-lime-300 blur-sm"></div>
                <div class="absolute bottom-24 right-28 h-7 w-7 rounded-full bg-red-400/60 blur-sm"></div>

                <!-- Content -->
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-16 w-16 rounded-full bg-white/10 backdrop-blur-sm p-2 flex items-center justify-center border border-white/20">
                            <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <p class="text-2xl font-bold leading-tight">SMK N 1 Bangsri</p>
                            <p class="text-sm text-emerald-100 font-medium">Sistem Informasi Prestasi Siswa</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 max-w-md">
                    <p class="mb-6 text-xs font-bold uppercase tracking-[0.25em] text-lime-200">Portal Pengelolaan Admin</p>
                    <h2 class="text-5xl font-bold leading-tight xl:text-6xl mb-2">Rawat Setiap Pencapaian Siswa</h2>
                    <p class="mt-6 text-sm leading-7 text-emerald-50 opacity-90">
                        Kelola data prestasi sekolah dengan rapi, cepat, dan terhubung dalam satu ruang kerja yang aman dan terpercaya.
                    </p>
                    <div class="mt-8 flex items-center gap-3 text-xs text-emerald-100">
                        <div class="h-2 w-2 rounded-full bg-lime-300"></div>
                        <span class="font-medium">Akses aman untuk administrator sekolah</span>
                    </div>
                </div>

                <!-- Features -->
                <div class="relative z-10 grid grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="h-8 w-8 rounded-lg bg-lime-300/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-shield text-lime-300 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-emerald-100">Keamanan Tinggi</p>
                            <p class="text-xs text-emerald-200/80">Enkripsi end-to-end</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="h-8 w-8 rounded-lg bg-lime-300/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-bolt text-lime-300 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-emerald-100">Performa Cepat</p>
                            <p class="text-xs text-emerald-200/80">Loading instant</p>
                        </div>
                    </div>
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

        function handleSubmit() {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');

            // Validate form
            const form = document.getElementById('loginForm');
            if (!form.checkValidity()) {
                return;
            }

            // Show loading state
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');

            // Submit the form
            form.submit();
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
