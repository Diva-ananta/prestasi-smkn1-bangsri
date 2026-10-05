@extends('layouts.public')

@section('title', 'Tentang Kami - SMK N 1 Bangsri')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    <section class="border-b border-emerald-100 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 pb-10 pt-12 text-center sm:px-6 lg:px-8 lg:pb-14 lg:pt-16">
            <div class="animate-fade-in">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-700 dark:text-emerald-400">Profil sistem</p>
                <h1 class="mt-3 break-words text-3xl font-extrabold tracking-tight sm:text-5xl">
                    <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent dark:from-emerald-400 dark:to-blue-400">Tentang SIPRES</span>
                </h1>
                <p class="page-subtitle mx-auto mt-4 max-w-xl text-sm leading-7 sm:text-base">
                    Sistem informasi untuk mencatat, mengelola, dan membagikan pencapaian siswa SMK Negeri 1 Bangsri.
                </p>
            </div>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8">
        <section class="mb-8 grid animate-fade-in grid-cols-[64px_minmax(0,1fr)] items-start gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:mb-10 sm:grid-cols-[88px_minmax(0,1fr)] sm:gap-7 sm:p-8 lg:grid-cols-[200px_minmax(0,1fr)]">
            <div class="flex min-w-0 flex-col items-center text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 p-2 dark:bg-emerald-950/40 sm:h-20 sm:w-20 lg:h-28 lg:w-28">
                    <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK Negeri 1 Bangsri" class="h-full w-full object-contain" loading="lazy">
                </div>
                <p class="mt-2 text-[9px] font-bold uppercase leading-4 tracking-wide text-emerald-800 dark:text-emerald-300 sm:text-[10px]">SIPRES ESKASABA</p>
            </div>

            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-emerald-700 dark:text-emerald-400">Mengenal SIPRES</p>
                <h2 class="mt-2 text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Setiap pencapaian punya cerita.</h2>
                <p class="mt-3 text-sm leading-6 text-slate-700 dark:text-slate-300 sm:text-base sm:leading-7">
                    <span class="font-semibold text-emerald-700 dark:text-emerald-400">Sistem Informasi Prestasi Siswa (SIPRES)</span> adalah platform digital SMK Negeri 1 Bangsri untuk mencatat, mengelola, dan membagikan pencapaian siswa. Setiap prestasi menjadi bagian dari perjalanan dan kebanggaan siswa.
                </p>
                <p class="mt-3 text-sm leading-6 text-slate-700 dark:text-slate-300 sm:text-base sm:leading-7">
                    Data yang tersusun dan terverifikasi membantu sekolah menyampaikan informasi secara terbuka kepada siswa, orang tua, dan masyarakat. SIPRES juga mendorong siswa untuk terus berkembang, baik di bidang akademik maupun non-akademik.
                </p>
                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-600 dark:border-slate-800 dark:text-slate-300">
                    <span><i class="fas fa-check mr-1.5 text-emerald-600" aria-hidden="true"></i>Transparan</span>
                    <span><i class="fas fa-check mr-1.5 text-emerald-600" aria-hidden="true"></i>Terverifikasi</span>
                    <span><i class="fas fa-check mr-1.5 text-emerald-600" aria-hidden="true"></i>Mengapresiasi prestasi</span>
                </div>
            </div>
        </section>

        <section class="mb-10 animate-fade-in">
            <div class="mb-10">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-700 dark:text-emerald-400">Cara Kerja</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">Bagaimana Sistem Bekerja</h2>
            </div>

            <div class="space-y-4">
                @foreach([
                    ['step' => '1', 'icon' => 'pen-to-square', 'title' => 'Pencatatan Prestasi', 'desc' => 'Admin sekolah mencatat prestasi siswa dengan detail lengkap seperti nama lomba, hasil, tingkat, dan dokumentasi foto.'],
                    ['step' => '2', 'icon' => 'check-double', 'title' => 'Verifikasi Data', 'desc' => 'Setiap data divalidasi agar akurat, valid, dan sesuai dengan bukti yang tersedia.'],
                    ['step' => '3', 'icon' => 'globe', 'title' => 'Publikasi Online', 'desc' => 'Prestasi yang sudah diverifikasi dipublikasikan ke portal agar mudah diakses siswa, orang tua, dan masyarakat.'],
                    ['step' => '4', 'icon' => 'chart-bar', 'title' => 'Analitik & Laporan', 'desc' => 'Sistem menampilkan ringkasan dan trend prestasi untuk mendukung evaluasi sekolah.']
                ] as $index => $step)
                    <div class="animate-fade-in group relative flex gap-3 rounded-2xl border-2 border-slate-100 bg-white p-4 shadow-sm transition duration-300 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-600 sm:gap-6 sm:p-6 lg:p-8" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 sm:h-16 sm:w-16">
                            <span class="text-xl sm:text-2xl">{{ $step['step'] }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 sm:h-10 sm:w-10">
                                    <x-icon :name="$step['icon']" class="text-sm" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white sm:text-lg">{{ $step['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-400 sm:text-base">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="mb-12 animate-fade-in flex justify-center">
            <div class="inline-flex items-center gap-3 rounded-full border border-emerald-200 bg-emerald-50 px-6 py-4 dark:border-emerald-900/40 dark:bg-emerald-950/30">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <x-icon name="shield-check" />
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">✓ Terverifikasi</p>
                    <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-100">Data dipastikan akurat oleh pihak SMK N 1 Bangsri</p>
                </div>
            </div>
        </div>

        <section class="rounded-2xl bg-emerald-900 px-6 py-9 text-white shadow-md sm:px-10 sm:py-11">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Siap Menjelajahi Prestasi?</h2>
                <p class="mt-4 text-lg text-emerald-100">Akses portal lengkap untuk melihat semua prestasi siswa dan data analitik sekolah kami.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('public.prestasi.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-lime-300 px-5 py-3 text-sm font-bold leading-5 text-slate-950 shadow-2xl shadow-lime-300/40 transition hover:bg-lime-200 active:scale-95 sm:px-6">
                        <x-icon name="arrow-right" /> Portal Prestasi
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 px-5 py-3 text-sm font-bold leading-5 text-white backdrop-blur-sm transition hover:bg-white/20 hover:border-white/50 sm:px-6">
                        <x-icon name="home" /> Beranda
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-10 border-t border-slate-200 py-8 dark:border-slate-800 sm:mt-12 sm:py-10">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-700 dark:text-emerald-400">Dikembangkan oleh</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">Akasa Dev</h2>
                </div>
                <span class="w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">2026</span>
            </div>
            <ol class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <li>
                    <a href="https://www.instagram.com/khnsaskiaa/" target="_blank" rel="noopener noreferrer" class="group flex h-full min-h-16 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-emerald-700 dark:focus-visible:outline-emerald-400">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-xs font-bold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">01</span>
                        <span class="min-w-0 flex-1">Askia Khoirun Nisa</span>
                        <i class="fab fa-instagram shrink-0 text-base text-slate-400 transition group-hover:text-pink-500" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/ekakusnaini/" target="_blank" rel="noopener noreferrer" class="group flex h-full min-h-16 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-emerald-700 dark:focus-visible:outline-emerald-400">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-xs font-bold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">02</span>
                        <span class="min-w-0 flex-1">Eka Kusnaini</span>
                        <i class="fab fa-instagram shrink-0 text-base text-slate-400 transition group-hover:text-pink-500" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/hii_divaaa/" target="_blank" rel="noopener noreferrer" class="group flex h-full min-h-16 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-emerald-700 dark:focus-visible:outline-emerald-400">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-xs font-bold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">03</span>
                        <span class="min-w-0 flex-1">Saka Diva Ananta</span>
                        <i class="fab fa-instagram shrink-0 text-base text-slate-400 transition group-hover:text-pink-500" aria-hidden="true"></i>
                    </a>
                </li>
            </ol>
        </section>
    </main>
</div>
@endsection