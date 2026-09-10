@extends('layouts.admin')

@section('title', 'SiPintu Gateway')

@section('content')
<div class="sipintu-page space-y-6" x-data="sipintuHub()">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 rounded-3xl bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 p-6 text-white shadow-xl lg:flex-row lg:items-center lg:justify-between lg:p-8">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-emerald-200 backdrop-blur-sm">
                <span class="h-2 w-2 rounded-full {{ ($pingResult['success'] ?? false) ? 'bg-lime-400 animate-ping' : 'bg-rose-400' }}"></span>
                <span>SiPintu Identity & API Gateway Integration</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight lg:text-3xl">Pusat Integrasi SiPintu</h1>
            <p class="max-w-2xl text-sm text-emerald-100">
                Terhubung dengan SIJUNA Service untuk sinkronisasi data siswa & alumni otomatis serta Single Sign-On (OAuth 2.0 / OpenID Connect).
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button
                type="button"
                @click="runPing()"
                :disabled="pingLoading"
                class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-xs font-bold text-white backdrop-blur transition hover:bg-white/25 active:scale-95 disabled:opacity-50"
            >
                <i class="fas fa-satellite-dish" :class="pingLoading ? 'animate-spin' : ''"></i>
                <span x-text="pingLoading ? 'Memeriksa...' : 'Uji Ping Gateway'"></span>
            </button>
            <button
                type="button"
                @click="runValidate()"
                :disabled="validateLoading"
                class="inline-flex items-center gap-2 rounded-xl bg-lime-400 px-4 py-2.5 text-xs font-bold text-slate-900 shadow-lg shadow-lime-900/20 transition hover:bg-lime-300 active:scale-95 disabled:opacity-50"
            >
                <i class="fas fa-shield-check" :class="validateLoading ? 'animate-spin' : ''"></i>
                <span x-text="validateLoading ? 'Memvalidasi...' : 'Validasi Kredensial'"></span>
            </button>
        </div>
    </div>

    <!-- Status Cards Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Gateway Status -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Status Server</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <i class="fas fa-server text-sm"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-bold {{ ($pingResult['success'] ?? false) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ ($pingResult['success'] ?? false) ? 'bg-emerald-600 dark:bg-emerald-400' : 'bg-rose-600 dark:bg-rose-400' }}"></span>
                    <span x-text="statusOnline ? 'Online & Terhubung' : 'Offline / Kendala'">{{ ($pingResult['success'] ?? false) ? 'Online & Terhubung' : 'Offline / Kendala' }}</span>
                </span>
            </div>
            <p class="mt-2 truncate text-xs text-slate-500 dark:text-slate-400">{{ $config['base_url'] }}</p>
        </div>

        <!-- Latency -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Latency API</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-950/50 dark:text-teal-400">
                    <i class="fas fa-bolt text-sm"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-800 dark:text-white" x-text="latency + ' ms'">{{ $pingResult['latency_ms'] ?? 0 }} ms</span>
            </div>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Kecepatan respons gateway</p>
        </div>

        <!-- Siswa Aktif Lokal -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Siswa Aktif Lokal</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <i class="fas fa-user-check text-sm"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-700 dark:text-emerald-400" x-text="localAktifCount">{{ $totalSiswaAktif }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Siswa berkelas</span>
            </div>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Kelas aktif (Bukan alumni)</p>
        </div>

        <!-- Alumni Lokal -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Alumni Lokal</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400">
                    <i class="fas fa-graduation-cap text-sm"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-purple-700 dark:text-purple-400" x-text="localAlumniCount">{{ $totalAlumni }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Alumni tersimpan</span>
            </div>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">classroom = null di SIJUNA</p>
        </div>
    </div>

    <!-- Main Section: Sync & Tabs -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left: Sinkronisasi Data (Server-to-Server) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                        <i class="fas fa-sync-alt text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Sinkronisasi Siswa SIJUNA</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Server-to-Server SIJUNA API</p>
                    </div>
                </div>

                <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                    Data dari SIJUNA akan difilter secara otomatis: yang memiliki kelas dimasukkan sebagai <strong>Siswa Aktif</strong>, sedangkan yang tidak memiliki kelas (<code class="text-purple-600 dark:text-purple-400 font-mono">classroom: null</code>) dikelompokkan ke <strong>Alumni</strong>.
                </p>

                <!-- Pilihan Jenis Sinkronisasi -->
                <div class="mt-5 space-y-3 rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Pilih Data yang Disinkronkan</p>
                    
                    <div class="space-y-2">
                        <label class="flex items-start gap-2.5 p-2 rounded-xl border border-transparent hover:bg-white dark:hover:bg-slate-800 cursor-pointer transition" :class="syncType === 'siswa' ? 'bg-white shadow-sm border-emerald-300 dark:border-emerald-700' : ''">
                            <input type="radio" name="syncType" value="siswa" x-model="syncType" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-white">Siswa Aktif Saja</p>
                                <p class="text-[11px] text-slate-500">Hanya siswa yang memiliki kelas aktif (~1.160 siswa)</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 p-2 rounded-xl border border-transparent hover:bg-white dark:hover:bg-slate-800 cursor-pointer transition" :class="syncType === 'alumni' ? 'bg-white shadow-sm border-purple-300 dark:border-purple-700' : ''">
                            <input type="radio" name="syncType" value="alumni" x-model="syncType" class="mt-0.5 text-purple-600 focus:ring-purple-500">
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-white">Alumni Saja</p>
                                <p class="text-[11px] text-slate-500">Siswa dengan classroom = null (~1.146 alumni)</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 p-2 rounded-xl border border-transparent hover:bg-white dark:hover:bg-slate-800 cursor-pointer transition" :class="syncType === 'all' ? 'bg-white shadow-sm border-teal-300 dark:border-teal-700' : ''">
                            <input type="radio" name="syncType" value="all" x-model="syncType" class="mt-0.5 text-teal-600 focus:ring-teal-500">
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-white">Semua (Siswa Aktif & Alumni)</p>
                                <p class="text-[11px] text-slate-500">Otomatis dipisahkan ke Siswa Aktif dan Alumni</p>
                            </div>
                        </label>
                    </div>

                    <div class="pt-2 border-t border-slate-200 dark:border-slate-700">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Batasi Jumlah Data (Opsional)</label>
                        <select x-model="syncLimit" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 focus:border-emerald-500 focus:outline-none">
                            <option value="">Semua Data yang Dipilih</option>
                            <option value="50">50 Data Pertama (Uji Coba Cepat)</option>
                            <option value="200">200 Data</option>
                            <option value="500">500 Data</option>
                        </select>
                    </div>
                </div>

                <!-- Info Guru Note -->
                <div class="mt-4 flex items-start gap-2.5 rounded-xl bg-amber-50 p-3 text-amber-900 dark:bg-amber-950/40 dark:text-amber-300 text-[11px] border border-amber-200 dark:border-amber-900/60">
                    <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                    <div>
                        <span class="font-bold">Info Data Guru:</span> Data Guru tidak dimasukkan ke tabel database siswa. Guru diakses secara independen melalui <strong>Tab Guru</strong>.
                    </div>
                </div>

                <div class="mt-5">
                    <button
                        type="button"
                        @click="runSync()"
                        :disabled="syncLoading"
                        class="w-full flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-r from-emerald-700 to-teal-700 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-900/25 transition duration-200 hover:from-emerald-800 hover:to-teal-800 active:scale-[0.98] disabled:opacity-75 disabled:cursor-wait"
                    >
                        <template x-if="!syncLoading">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-cloud-arrow-down text-base"></i>
                                <span x-text="getSyncButtonLabel()">Mulai Sinkronisasi</span>
                            </span>
                        </template>
                        <template x-if="syncLoading">
                            <span class="flex items-center gap-2 text-lime-200 font-bold">
                                <i class="fas fa-circle-notch animate-spin text-base"></i>
                                <span>Sedang Menyinkronkan...</span>
                            </span>
                        </template>
                    </button>

                    <!-- Loading Status Card -->
                    <div x-show="syncLoading" x-transition class="mt-4 rounded-2xl border border-emerald-300 bg-emerald-50/90 p-4 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/60" style="display: none;">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-700 text-white shadow-md shadow-emerald-900/20">
                                <i class="fas fa-arrows-rotate animate-spin text-base"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-bold text-emerald-950 dark:text-emerald-200">Sinkronisasi Sedang Berlangsung</p>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-200/80 px-2 py-0.5 text-[10px] font-bold text-emerald-900 dark:bg-emerald-800 dark:text-emerald-200 animate-pulse">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                        Mengunduh...
                                    </span>
                                </div>
                                <p class="text-[11px] text-emerald-800/80 dark:text-emerald-300/70 mt-1">
                                    Menghubungi SIJUNA Gateway & menyimpan ke database lokal. Data akan langsung muncul di tabel.
                                </p>
                            </div>
                        </div>
                        <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-emerald-200/70 dark:bg-emerald-900/70">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-lime-400 rounded-full animate-pulse w-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Sync Result Alert -->
                <div x-show="syncResult" class="mt-4 rounded-xl p-4 text-xs" :class="syncResult && syncResult.success ? 'border border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-200'" style="display: none;">
                    <div class="flex items-start gap-2">
                        <i class="fas mt-0.5" :class="syncResult && syncResult.success ? 'fa-check-circle text-emerald-600' : 'fa-triangle-exclamation text-rose-600'"></i>
                        <div class="flex-1">
                            <p class="font-bold" x-text="syncResult ? syncResult.message : ''"></p>
                            <template x-if="syncResult && syncResult.success">
                                <div class="mt-2 grid grid-cols-3 gap-2 text-center text-[11px]">
                                    <div class="rounded-lg bg-white/60 p-2 dark:bg-slate-900/60">
                                        <p class="text-slate-500">Baru</p>
                                        <p class="font-bold text-emerald-700 dark:text-emerald-400" x-text="syncResult.created"></p>
                                    </div>
                                    <div class="rounded-lg bg-white/60 p-2 dark:bg-slate-900/60">
                                        <p class="text-slate-500">Diupdate</p>
                                        <p class="font-bold text-blue-700 dark:text-blue-400" x-text="syncResult.updated"></p>
                                    </div>
                                    <div class="rounded-lg bg-white/60 p-2 dark:bg-slate-900/60">
                                        <p class="text-slate-500">Total Diproses</p>
                                        <p class="font-bold text-slate-800 dark:text-slate-200" x-text="syncResult.total"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OAuth 2.0 Info Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-teal-100 text-teal-700 dark:bg-teal-950/60 dark:text-teal-400">
                        <i class="fas fa-shield-alt text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-white">Single Sign-On (OAuth 2.0)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Metode 2: Autentikasi Pengguna</p>
                    </div>
                </div>
                <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500">Client ID Terdaftar:</span>
                        <code class="text-slate-800 dark:text-slate-200 font-mono text-[11px]">{{ $config['client_id'] }}</code>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500">Redirect URI Callback:</span>
                        <code class="text-slate-800 dark:text-slate-200 font-mono text-[11px]">{{ $config['redirect_uri'] }}</code>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500">Alur Autentikasi:</span>
                        <span class="font-semibold text-emerald-700 dark:text-emerald-400">Authorization Code Grant</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('oauth.sipintu.redirect') }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        <span>Uji Endpoint Otorisasi SSO</span>
                        <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Tabbed Gateway View (Siswa Aktif, Alumni, Guru) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <!-- Navigation Tabs -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4 mb-5">
                    <div class="flex items-center gap-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl">
                        <!-- Tab Siswa -->
                        <button
                            type="button"
                            @click="switchTab('siswa')"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition duration-200"
                            :class="activeTab === 'siswa' ? 'bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                        >
                            <i class="fas fa-user-graduate"></i>
                            <span>Siswa Aktif</span>
                            <span class="rounded-full bg-emerald-100 dark:bg-emerald-950/80 px-2 py-0.5 text-[10px] text-emerald-800 dark:text-emerald-300 font-extrabold" x-text="studentResults.length"></span>
                        </button>

                        <!-- Tab Alumni -->
                        <button
                            type="button"
                            @click="switchTab('alumni')"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition duration-200"
                            :class="activeTab === 'alumni' ? 'bg-white dark:bg-slate-900 text-purple-700 dark:text-purple-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                        >
                            <i class="fas fa-graduation-cap"></i>
                            <span>Alumni</span>
                            <span class="rounded-full bg-purple-100 dark:bg-purple-950/80 px-2 py-0.5 text-[10px] text-purple-800 dark:text-purple-300 font-extrabold" x-text="alumniResults.length"></span>
                        </button>

                        <!-- Tab Guru -->
                        <button
                            type="button"
                            @click="switchTab('guru')"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition duration-200"
                            :class="activeTab === 'guru' ? 'bg-white dark:bg-slate-900 text-teal-700 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                        >
                            <i class="fas fa-chalkboard-user"></i>
                            <span>Guru (SIJUNA)</span>
                            <span class="rounded-full bg-teal-100 dark:bg-teal-950/80 px-2 py-0.5 text-[10px] text-teal-800 dark:text-teal-300 font-extrabold" x-text="teacherResults.length"></span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            <i class="fas fa-users text-emerald-600"></i>
                            <span>Buka Master Siswa</span>
                        </a>
                    </div>
                </div>

                <!-- Tab Description Banner -->
                <div class="mb-4">
                    <template x-if="activeTab === 'siswa'">
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                            <p><strong class="text-emerald-700 dark:text-emerald-400">Tab Siswa Aktif:</strong> Menampilkan siswa yang terdaftar aktif memiliki kelas di SIJUNA (classroom ≠ null).</p>
                        </div>
                    </template>
                    <template x-if="activeTab === 'alumni'">
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                            <p><strong class="text-purple-700 dark:text-purple-400">Tab Alumni:</strong> Menampilkan data lulusan / alumni SMK N 1 Bangsri (<code class="text-purple-600 font-mono">classroom: null</code> di SIJUNA).</p>
                        </div>
                    </template>
                    <template x-if="activeTab === 'guru'">
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                            <p><strong class="text-teal-700 dark:text-teal-400">Tab Guru SIJUNA:</strong> Menampilkan data guru langsung dari SIJUNA Gateway (Server-to-Server, tidak dimasukkan ke tabel siswa).</p>
                        </div>
                    </template>
                </div>

                <!-- Search Inputs: Siswa & Alumni -->
                <template x-if="activeTab === 'siswa' || activeTab === 'alumni'">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 mb-4">
                        <div class="sm:col-span-4">
                            <input
                                type="text"
                                x-model="searchNis"
                                @keydown.enter="searchApi()"
                                placeholder="Cari NIS (misal: 4439)"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            >
                        </div>
                        <div class="sm:col-span-6">
                            <input
                                type="text"
                                x-model="searchKeyword"
                                @keydown.enter="searchApi()"
                                :placeholder="activeTab === 'siswa' ? 'Cari nama siswa aktif...' : 'Cari nama alumni...'"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            >
                        </div>
                        <div class="sm:col-span-2">
                            <button
                                type="button"
                                @click="searchApi()"
                                :disabled="searchLoading"
                                class="w-full h-full min-h-[38px] flex items-center justify-center gap-1.5 rounded-xl bg-emerald-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-800 active:scale-95 disabled:opacity-50"
                            >
                                <i class="fas fa-search" :class="searchLoading ? 'animate-spin' : ''"></i>
                                <span>Cari</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Search Inputs: Guru -->
                <template x-if="activeTab === 'guru'">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 mb-4">
                        <div class="sm:col-span-4">
                            <input
                                type="text"
                                x-model="searchNip"
                                @keydown.enter="searchTeachersApi()"
                                placeholder="Cari NIP Guru..."
                                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-teal-600 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            >
                        </div>
                        <div class="sm:col-span-6">
                            <input
                                type="text"
                                x-model="searchKeyword"
                                @keydown.enter="searchTeachersApi()"
                                placeholder="Cari nama guru / panggilan..."
                                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-teal-600 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            >
                        </div>
                        <div class="sm:col-span-2">
                            <button
                                type="button"
                                @click="searchTeachersApi()"
                                :disabled="searchLoading"
                                class="w-full h-full min-h-[38px] flex items-center justify-center gap-1.5 rounded-xl bg-teal-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-teal-800 active:scale-95 disabled:opacity-50"
                            >
                                <i class="fas fa-search" :class="searchLoading ? 'animate-spin' : ''"></i>
                                <span>Cari Guru</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- TABEL DATA -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-[680px] w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="px-4 py-3">NIS / NISN</th>
                                <th class="px-4 py-3">Nama Siswa</th>
                                <th class="px-4 py-3">JK</th>
                                <th class="px-4 py-3">Kelas / Jurusan</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <template x-if="searchLoading">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                        <i class="fas fa-circle-notch animate-spin text-xl text-emerald-600 mb-2"></i>
                                        <p>Menghubungi SiPintu API Gateway...</p>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!searchLoading && studentResults.length === 0">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                        <i class="fas fa-inbox text-2xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                        <p>Silakan masukkan NIS atau nama siswa untuk mencari langsung dari SiPintu Gateway.</p>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="student in studentResults" :key="student.id || student.nis">
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-4 py-3 font-mono font-bold text-slate-800 dark:text-slate-200" x-text="student.nis"></td>
                                    <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white" x-text="student.nama || student.name"></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold" :class="(student.jk == 1 || student.jk == 'L') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300'" x-text="(student.jk == 1 || student.jk == 'L') ? 'L' : 'P'"></span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400" x-text="student.classroom ? student.classroom.name : (student.kelas || '-')"></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                            SIJUNA
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- API Response Preview (Collapsible) -->
                <div class="mt-4" x-data="{ showRaw: false }">
                    <button type="button" @click="showRaw = !showRaw" class="text-[11px] font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 flex items-center gap-1">
                        <i class="fas" :class="showRaw ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        <span x-text="showRaw ? 'Sembunyikan Raw JSON Response' : 'Lihat Raw JSON Response Gateway'"></span>
                    </button>
                    <div x-show="showRaw" class="mt-2 rounded-xl bg-slate-950 p-4 text-[11px] font-mono text-emerald-400 overflow-x-auto max-h-60" style="display: none;">
                        <pre x-text="rawJson || 'Belum ada data request.'"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sipintuHub() {
        return {
            activeTab: 'siswa',
            syncType: 'siswa',
            statusOnline: {{ ($pingResult['success'] ?? false) ? 'true' : 'false' }},
            latency: {{ $pingResult['latency_ms'] ?? 0 }},
            localAktifCount: {{ $totalSiswaAktif ?? 0 }},
            localAlumniCount: {{ $totalAlumni ?? 0 }},
            pingLoading: false,
            validateLoading: false,
            syncLoading: false,
            searchLoading: false,
            teachersLoading: false,
            syncLimit: '50',
            searchNis: '',
            searchNip: '',
            searchKeyword: '',
            studentResults: @json($recentStudents ?? []),
            alumniResults: @json($recentAlumni ?? []),
            teacherResults: [],
            rawJson: '',
            syncResult: null,

            getSyncButtonLabel() {
                if (this.syncType === 'alumni') return 'Sinkronisasi Alumni Saja';
                if (this.syncType === 'all') return 'Sinkronisasi Semua Data';
                return 'Sinkronisasi Siswa Aktif';
            },

            switchTab(tab) {
                this.activeTab = tab;
                this.searchKeyword = '';
                this.searchNis = '';
                this.searchNip = '';
                if (tab === 'guru' && this.teacherResults.length === 0) {
                    this.loadTeachers();
                }
            },

            async runPing() {
                this.pingLoading = true;
                try {
                    const res = await fetch("{{ route('admin.sipintu.ping') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await res.json();
                    this.statusOnline = data.success;
                    this.latency = data.latency_ms;
                    this.rawJson = JSON.stringify(data, null, 2);
                    window.adminNotify(data.message || (data.success ? 'Gateway SiPintu Online' : 'Koneksi gagal'), data.success ? 'success' : 'error');
                } catch (e) {
                    window.adminNotify('Gagal menghubungi endpoint ping lokal.', 'error');
                } finally {
                    this.pingLoading = false;
                }
            },

            async runValidate() {
                this.validateLoading = true;
                try {
                    const res = await fetch("{{ route('admin.sipintu.validate-client') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await res.json();
                    this.rawJson = JSON.stringify(data, null, 2);
                    window.adminNotify(data.message || 'Kredensial terverifikasi.', data.success ? 'success' : 'error');
                } catch (e) {
                    window.adminNotify('Gagal menghubungi endpoint validasi.', 'error');
                } finally {
                    this.validateLoading = false;
                }
            },

            async runSync() {
                const label = this.syncType === 'alumni' ? 'Alumni Saja' : (this.syncType === 'siswa' ? 'Siswa Aktif Saja' : 'Semua (Siswa Aktif & Alumni)');
                if (!confirm(`Mulai proses sinkronisasi (${label}) dari SiPintu Gateway ke database lokal?`)) {
                    return;
                }
                this.syncLoading = true;
                this.syncResult = null;
                try {
                    const res = await fetch("{{ route('admin.sipintu.sync-students') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            limit: this.syncLimit,
                            type: this.syncType
                        })
                    });
                    const data = await res.json();
                    this.syncResult = data;
                    if (data.success) {
                        // Perbarui data di tab yang relevan
                        if (data.students && data.students.length > 0) {
                            if (this.syncType === 'alumni') {
                                this.alumniResults = data.students;
                                this.activeTab = 'alumni';
                                this.localAlumniCount += (data.created || 0);
                            } else if (this.syncType === 'siswa') {
                                this.studentResults = data.students;
                                this.activeTab = 'siswa';
                                this.localAktifCount += (data.created || 0);
                            } else {
                                this.studentResults = data.students.filter(s => s.status !== 'Alumni');
                                this.alumniResults = data.students.filter(s => s.status === 'Alumni');
                                this.localAktifCount += (data.created || 0);
                            }
                        }
                        this.rawJson = JSON.stringify(data, null, 2);
                        window.adminNotify(data.message, 'success');
                    } else {
                        this.rawJson = JSON.stringify(data, null, 2);
                        window.adminNotify(data.message, 'error');
                    }
                } catch (e) {
                    window.adminNotify('Terjadi kesalahan saat memproses sinkronisasi.', 'error');
                } finally {
                    this.syncLoading = false;
                }
            },

            async searchApi() {
                if (!this.searchNis && !this.searchKeyword) {
                    window.adminNotify('Masukkan NIS atau nama untuk mencari.', 'error');
                    return;
                }
                this.searchLoading = true;
                try {
                    const params = new URLSearchParams();
                    if (this.searchNis) params.append('nis', this.searchNis);
                    if (this.searchKeyword) params.append('search', this.searchKeyword);
                    params.append('type', this.activeTab); // 'siswa' or 'alumni'

                    const res = await fetch(`{{ route('admin.sipintu.search-students') }}?${params.toString()}`);
                    const json = await res.json();
                    this.rawJson = JSON.stringify(json, null, 2);
                    if (json.success) {
                        if (this.activeTab === 'siswa') {
                            this.studentResults = json.data || [];
                        } else {
                            this.alumniResults = json.data || [];
                        }
                        const count = (json.data || []).length;
                        if (count === 0) {
                            window.adminNotify('Tidak ditemukan data dengan kriteria tersebut di SiPintu Gateway.', 'error');
                        }
                    } else {
                        window.adminNotify(json.message || 'Pencarian gagal.', 'error');
                    }
                } catch (e) {
                    window.adminNotify('Gagal mencari ke SiPintu API.', 'error');
                } finally {
                    this.searchLoading = false;
                }
            },

            async loadTeachers() {
                this.teachersLoading = true;
                try {
                    const res = await fetch(`{{ route('admin.sipintu.search-teachers') }}`);
                    const json = await res.json();
                    if (json.success) {
                        this.teacherResults = json.data || [];
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.teachersLoading = false;
                }
            },

            async searchTeachersApi() {
                this.searchLoading = true;
                try {
                    const params = new URLSearchParams();
                    if (this.searchNip) params.append('nip', this.searchNip);
                    if (this.searchKeyword) params.append('search', this.searchKeyword);

                    const res = await fetch(`{{ route('admin.sipintu.search-teachers') }}?${params.toString()}`);
                    const json = await res.json();
                    this.rawJson = JSON.stringify(json, null, 2);
                    if (json.success) {
                        this.teacherResults = json.data || [];
                        if (this.teacherResults.length === 0) {
                            window.adminNotify('Tidak ditemukan data guru dengan kriteria tersebut.', 'error');
                        }
                    } else {
                        window.adminNotify(json.message || 'Pencarian gagal.', 'error');
                    }
                } catch (e) {
                    window.adminNotify('Gagal mencari data guru ke SiPintu API.', 'error');
                } finally {
                    this.searchLoading = false;
                }
            }
        }
    }
</script>
@endsection
