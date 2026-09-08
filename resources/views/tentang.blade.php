@extends('layouts.public')

@section('title', 'Tentang Kami - SMK N 1 Bangsri')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    <section class="border-b border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80">
        <div class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8 lg:py-11">
            <div class="animate-fade-in">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Tentang Sistem
                </div>
                <h1 class="mt-4 text-4xl font-black tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                    Tentang Sistem <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent">Informasi Prestasi</span>
                </h1>
                <p class="mt-6 max-w-3xl text-base leading-7 text-slate-600 dark:text-slate-300">
                    Platform inovatif yang mendokumentasikan, mengelola, dan merayakan pencapaian luar biasa siswa-siswi SMK N 1 Bangsri dengan profesional, terbuka, dan akuntabel.
                </p>
            </div>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8">
        <section class="mb-10 animate-fade-in rounded-2xl border border-slate-200 bg-white p-6 shadow-lg dark:border-slate-800 dark:bg-slate-900 sm:p-8">
            <p class="text-lg leading-8 text-slate-700 dark:text-slate-300">
                <span class="font-bold text-emerald-600 dark:text-emerald-400">Sistem Informasi Prestasi Siswa (SIPS)</span> adalah platform digital yang dirancang khusus untuk mendokumentasikan, mengelola, dan mempublikasikan pencapaian siswa SMK N 1 Bangsri. Kami percaya bahwa setiap prestasi, dari yang paling sederhana hingga yang paling prestisius, layak mendapatkan apresiasi dan catatan yang abadi.
            </p>
            <p class="mt-6 text-lg leading-8 text-slate-700 dark:text-slate-300">
                Dibangun dengan prinsip <strong class="text-slate-900 dark:text-white">transparansi</strong>, <strong class="text-slate-900 dark:text-white">akuntabilitas</strong>, dan <strong class="text-slate-900 dark:text-white">kebanggaan almamater</strong>, sistem ini menjadi jembatan yang menghubungkan siswa, sekolah, orang tua, dan masyarakat luas. Melalui pendokumentasian yang teratur dan terverifikasi, kami mendorong seluruh peserta didik untuk terus berkembang di bidang akademik maupun non-akademik.
            </p>
        </section>

        <section class="mb-10 animate-fade-in">
            <div class="mb-10">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-700 dark:text-emerald-400">Manfaat</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">Untuk Siapa Saja</h2>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    [
                        'icon' => 'fa-graduation-cap',
                        'accent' => 'emerald',
                        'title' => 'Untuk Siswa',
                        'desc' => 'Platform untuk mendokumentasikan pencapaian dan membangun portofolio digital yang berguna untuk masa depan siswa.'
                    ],
                    [
                        'icon' => 'fa-school',
                        'accent' => 'blue',
                        'title' => 'Untuk Sekolah',
                        'desc' => 'Sistem manajemen prestasi yang terorganisir, memudahkan sekolah dalam mencatat dan melaporkan pencapaian siswa.'
                    ],
                    [
                        'icon' => 'fa-users-line',
                        'accent' => 'teal',
                        'title' => 'Untuk Masyarakat',
                        'desc' => 'Portal terbuka yang menampilkan prestasi siswa dan reputasi sekolah secara transparan dan profesional.'
                    ]
                ] as $index => $feature)
                    @php
                        $classes = [
                            'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/20 dark:text-emerald-300',
                            'blue' => 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-300',
                            'teal' => 'border-teal-200 bg-teal-50 text-teal-700 dark:border-teal-900/40 dark:bg-teal-950/20 dark:text-teal-300',
                        ][$feature['accent']];
                    @endphp
                    <div class="animate-fade-in rounded-[24px] border-2 bg-white p-8 shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:bg-slate-900 {{ $classes }}" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/80 text-2xl dark:bg-slate-900/60">
                            <i class="fas {{ $feature['icon'] }}"></i>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-slate-900 dark:text-white">{{ $feature['title'] }}</h3>
                        <p class="mt-3 text-slate-700 dark:text-slate-400">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-10 animate-fade-in">
            <div class="mb-10">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-700 dark:text-emerald-400">Cara Kerja</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">Bagaimana Sistem Bekerja</h2>
            </div>

            <div class="space-y-4">
                @foreach([
                    ['step' => '1', 'icon' => 'fa-pen-to-square', 'title' => 'Pencatatan Prestasi', 'desc' => 'Admin sekolah mencatat prestasi siswa dengan detail lengkap seperti nama lomba, hasil, tingkat, dan dokumentasi foto.'],
                    ['step' => '2', 'icon' => 'fa-check-double', 'title' => 'Verifikasi Data', 'desc' => 'Setiap data divalidasi agar akurat, valid, dan sesuai dengan bukti yang tersedia.'],
                    ['step' => '3', 'icon' => 'fa-globe', 'title' => 'Publikasi Online', 'desc' => 'Prestasi yang sudah diverifikasi dipublikasikan ke portal agar mudah diakses siswa, orang tua, dan masyarakat.'],
                    ['step' => '4', 'icon' => 'fa-chart-bar', 'title' => 'Analitik & Laporan', 'desc' => 'Sistem menampilkan ringkasan dan trend prestasi untuk mendukung evaluasi sekolah.']
                ] as $index => $step)
                    <div class="animate-fade-in group relative flex gap-6 rounded-2xl border-2 border-slate-100 bg-white p-6 shadow-sm transition duration-300 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-600 lg:p-8" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50 font-bold text-emerald-700 dark:from-emerald-950/50 dark:to-emerald-950/30 dark:text-emerald-400">
                            <span class="text-2xl">{{ $step['step'] }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                                    <i class="fas {{ $step['icon'] }} text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $step['title'] }}</h3>
                                    <p class="mt-2 text-slate-700 dark:text-slate-400">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="mb-12 animate-fade-in flex justify-center">
            <div class="inline-flex items-center gap-3 rounded-full border-2 border-emerald-200 bg-gradient-to-r from-emerald-50 to-blue-50 px-6 py-4 dark:border-emerald-900/40 dark:from-emerald-950/30 dark:to-blue-950/20">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">✓ Terverifikasi</p>
                    <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-100">Data dipastikan akurat oleh pihak SMK N 1 Bangsri</p>
                </div>
            </div>
        </div>

        <section class="rounded-2xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-blue-900 px-6 py-9 text-white shadow-2xl sm:px-10 sm:py-11">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Siap Menjelajahi Prestasi?</h2>
                <p class="mt-4 text-lg text-emerald-100">Akses portal lengkap untuk melihat semua prestasi siswa dan data analitik sekolah kami.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('public.prestasi.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-lime-300 px-5 py-3 text-sm font-bold leading-5 text-slate-950 shadow-2xl shadow-lime-300/40 transition hover:bg-lime-200 active:scale-95 sm:px-6">
                        <i class="fas fa-arrow-right"></i> Portal Prestasi
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 px-5 py-3 text-sm font-bold leading-5 text-white backdrop-blur-sm transition hover:bg-white/20 hover:border-white/50 sm:px-6">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
