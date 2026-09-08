@extends('layouts.admin')

@section('title', 'SiPintu Gateway')

@section('content')
<div class="space-y-6" x-data="sipintuHub()">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 rounded-3xl bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 p-6 text-white shadow-xl lg:flex-row lg:items-center lg:justify-between lg:p-8">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-emerald-200 backdrop-blur-sm">
                <span class="h-2 w-2 rounded-full {{ ($pingResult['success'] ?? false) ? 'bg-lime-400 animate-ping' : 'bg-rose-400' }}"></span>
                <span>SiPintu Identity & API Gateway Integration</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight lg:text-3xl">Pusat Integrasi SiPintu</h1>
            <p class="max-w-2xl text-sm text-emerald-100">
                Terhubung dengan SIJUNA Service untuk sinkronisasi data siswa otomatis dan Single Sign-On (OAuth 2.0 / OpenID Connect).
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
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Kecepatan respons jaringan</p>
        </div>

        <!-- Registered Client ID -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Client ID Terdaftar</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                    <i class="fas fa-fingerprint text-sm"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <code class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold text-slate-800 dark:bg-slate-800 dark:text-slate-200">{{ $config['client_id'] }}</code>
            </div>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Aplikasi: <strong>Prestasimu</strong></p>
        </div>

        <!-- Siswa Lokal -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Data Siswa Prestasimu</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">
                    <i class="fas fa-user-graduate text-sm"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-800 dark:text-white" x-text="localStudentCount">{{ $totalSiswaLokal }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Siswa tersimpan</span>
            </div>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Di database lokal sekolah</p>
        </div>
    </div>

    <!-- Main Section: Sync & Search -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left: Sinkronisasi Data Siswa (Server-to-Server) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                        <i class="fas fa-sync-alt text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Sinkronisasi Siswa SIJUNA</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Metode 1: Server-to-Server API Gateway</p>
                    </div>
                </div>

                <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                    Mengambil seluruh data siswa aktif dari server SiPintu (SIJUNA) secara aman menggunakan autentikasi Header (<code class="text-emerald-700 dark:text-emerald-400">X-Client-ID</code> & <code class="text-emerald-700 dark:text-emerald-400">X-Client-Secret</code>) dan menyimpannya ke tabel siswa Prestasimu.
                </p>

                <div class="mt-6 rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        <span>Pilihan Sinkronisasi</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Batasi Jumlah Data (Opsional)</label>
                            <select x-model="syncLimit" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 focus:border-emerald-500 focus:outline-none">
                                <option value="">Semua Siswa (~2.300+ data siswa)</option>
                                <option value="50">50 Siswa Pertama (Uji Coba Cepat)</option>
                                <option value="200">200 Siswa</option>
                                <option value="500">500 Siswa</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button
                        type="button"
                        @click="runSync()"
                        :disabled="syncLoading"
                        class="w-full flex items-center justify-center gap-2 rounded-2xl bg-emerald-700 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-800 active:scale-[0.98] disabled:opacity-50"
                    >
                        <i class="fas fa-cloud-arrow-down" :class="syncLoading ? 'animate-bounce' : ''"></i>
                        <span x-text="syncLoading ? 'Sedang Memproses Sinkronisasi...' : 'Mulai Sinkronisasi Sekarang'"></span>
                    </button>
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

            <!-- OAuth 2.0 SSO Info Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-teal-100 text-teal-700 dark:bg-teal-950/60 dark:text-teal-400">
                        <i class="fas fa-shield-alt text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-white">Single Sign-On (OAuth 2.0)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Metode 2: Login via SiPintu</p>
                    </div>
                </div>
                <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500">Redirect URI Callback:</span>
                        <code class="text-slate-800 dark:text-slate-200 font-mono text-[11px]">{{ $config['redirect_uri'] }}</code>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500">Alur Autentikasi:</span>
                        <span class="font-semibold text-emerald-700 dark:text-emerald-400">Authorization Code Grant</span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Status SSO:</span>
                        <span class="inline-flex items-center gap-1 text-slate-600 dark:text-slate-400 font-medium">
                            <i class="fas fa-shield text-[10px]"></i> Login Internal Diutamakan
                        </span>
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

        <!-- Right: Live Query & Siswa Search Directly from Gateway -->
        <div class="lg:col-span-7 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Pencarian Data Siswa Langsung (Gateway Live)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Endpoint: <code class="font-mono text-emerald-700 dark:text-emerald-400">GET /api/v1/sijuna/students</code></p>
                    </div>
                </div>

                <!-- Search Inputs -->
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
                            placeholder="Cari nama siswa..."
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

                <!-- Search Results Table -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
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
            statusOnline: {{ ($pingResult['success'] ?? false) ? 'true' : 'false' }},
            latency: {{ $pingResult['latency_ms'] ?? 0 }},
            localStudentCount: {{ $totalSiswaLokal }},
            pingLoading: false,
            validateLoading: false,
            syncLoading: false,
            searchLoading: false,
            syncLimit: '50',
            searchNis: '',
            searchKeyword: '',
            studentResults: [],
            rawJson: '',
            syncResult: null,

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
                if (!confirm('Apakah Anda yakin ingin memulai sinkronisasi data siswa dari SiPintu Gateway ke database lokal?')) {
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
                        body: JSON.stringify({ limit: this.syncLimit })
                    });
                    const data = await res.json();
                    this.syncResult = data;
                    if (data.success) {
                        this.localStudentCount += (data.created || 0);
                        window.adminNotify(data.message, 'success');
                    } else {
                        window.adminNotify(data.message, 'error');
                    }
                } catch (e) {
                    window.adminNotify('Terjadi kesalahan saat memproses sinkronisasi siswa.', 'error');
                } finally {
                    this.syncLoading = false;
                }
            },

            async searchApi() {
                if (!this.searchNis && !this.searchKeyword) {
                    window.adminNotify('Masukkan NIS atau kata kunci nama untuk mencari.', 'error');
                    return;
                }
                this.searchLoading = true;
                try {
                    const params = new URLSearchParams();
                    if (this.searchNis) params.append('nis', this.searchNis);
                    if (this.searchKeyword) params.append('search', this.searchKeyword);

                    const res = await fetch(`{{ route('admin.sipintu.search-students') }}?${params.toString()}`);
                    const json = await res.json();
                    this.rawJson = JSON.stringify(json, null, 2);
                    if (json.success) {
                        this.studentResults = json.data || [];
                        if (this.studentResults.length === 0) {
                            window.adminNotify('Tidak ditemukan siswa dengan kriteria tersebut di SiPintu Gateway.', 'error');
                        }
                    } else {
                        window.adminNotify(json.message || 'Pencarian gagal.', 'error');
                    }
                } catch (e) {
                    window.adminNotify('Gagal mencari ke SiPintu API.', 'error');
                } finally {
                    this.searchLoading = false;
                }
            }
        }
    }
</script>
@endsection
