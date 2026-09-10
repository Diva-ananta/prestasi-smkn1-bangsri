@extends('layouts.public')

@section('title', 'Sistem Informasi Prestasi Siswa SMK N 1 Bangsri')

@section('content')
@php
    $heroImage = $heroPrestasi?->foto
        ? asset('storage/' . $heroPrestasi->foto)
        : asset('images/logo-smk.png');
    $heroSlides = ($heroPrestasis ?? collect())->map(fn ($prestasi) => [
        'image' => asset('storage/' . $prestasi->foto),
        'title' => $prestasi->nama_lomba,
        'result' => $prestasi->hasil,
    ])->values();
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    $galleryImages = ($heroPrestasis ?? collect())->take(4)->values();
=======
    $galleryImages = ($galeriPrestasi ?? collect())->take(4)->values();
>>>>>>> Stashed changes
=======
    $galleryImages = ($galeriPrestasi ?? collect())->take(4)->values();
>>>>>>> Stashed changes
    $galleryImageOne = $galleryImages->get(0);
    $galleryImageTwo = $galleryImages->get(1);
    $galleryImageThree = $galleryImages->get(2);
    $galleryImageFour = $galleryImages->get(3);
    $services = [
        ['icon' => 'fa-trophy', 'title' => 'Galeri Prestasi', 'text' => 'Jelajahi pencapaian terbaik siswa dan sekolah dengan mudah.', 'class' => 'border-emerald-200 bg-emerald-50/70 text-emerald-700 dark:border-emerald-800/60 dark:bg-emerald-950/20 dark:text-emerald-300'],
        ['icon' => 'fa-users', 'title' => 'Data Siswa', 'text' => 'Temukan riwayat prestasi siswa dengan pencarian NIS.', 'class' => 'border-blue-200 bg-blue-50/70 text-blue-700 dark:border-blue-800/60 dark:bg-blue-950/20 dark:text-blue-300'],
        ['icon' => 'fa-chart-line', 'title' => 'Analitik', 'text' => 'Pantau perkembangan prestasi dengan data terukur.', 'class' => 'border-violet-200 bg-violet-50/70 text-violet-700 dark:border-violet-800/60 dark:bg-violet-950/20 dark:text-violet-300'],
        ['icon' => 'fa-folder-open', 'title' => 'Arsip', 'text' => 'Akses dokumentasi prestasi yang tersusun dengan rapi.', 'class' => 'border-amber-200 bg-amber-50/70 text-amber-700 dark:border-amber-800/60 dark:bg-amber-950/20 dark:text-amber-300'],
    ];
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">

    {{-- Notifikasi error pencarian siswa (sebelumnya tidak pernah tampil) --}}
    @if(session('search_error'))
        <div
            x-data="{ visible: true }"
            x-show="visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-[-1rem] opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-[-1rem] opacity-0"
            x-init="setTimeout(() => visible = false, 4500)"
            class="fixed inset-x-4 top-20 z-[60] mx-auto max-w-md sm:left-auto sm:right-6 sm:inset-x-auto"
        >
            <div class="relative flex items-start gap-3 rounded-2xl border-l-4 border-red-500 bg-red-50/95 px-4 py-3.5 text-sm font-medium text-red-700 shadow-xl backdrop-blur dark:border-red-500 dark:bg-red-950/90 dark:text-red-300">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100 text-xs dark:bg-red-900/50"><i class="fas fa-exclamation-circle"></i></span>
                <span>{{ session('search_error') }}</span>
                <button type="button" @click="visible = false" aria-label="Tutup notifikasi" class="absolute right-3 top-1/2 flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-full text-red-700/70 transition hover:bg-red-100 hover:text-red-900 dark:text-red-300/70 dark:hover:bg-red-900/40">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Hero Section with Slideshow -->
    <section x-data="heroSlideshow(@js($heroSlides))" x-init="start()" class="relative isolate overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-slate-950 text-white">
        <div class="absolute inset-0">
            <template x-for="(slide, index) in slides" :key="slide.image">
                <img x-show="active === index" x-transition.opacity.duration.1000ms :src="slide.image" :alt="slide.title" class="absolute inset-0 h-full w-full object-cover" :fetchpriority="index === 0 ? 'high' : 'auto'">
            </template>
            <img x-show="slides.length === 0" src="{{ $heroImage }}" alt="Prestasi siswa SMK N 1 Bangsri" class="h-full w-full object-cover" fetchpriority="high">

            <!-- Gradient Overlays -->
            <div class="absolute inset-0 bg-slate-950/50"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/40 to-blue-950/30"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-slate-950/60"></div>
        </div>

        <div class="relative mx-auto flex min-h-[640px] max-w-7xl flex-col items-center justify-center px-4 py-28 sm:px-6 lg:px-8">
            <div class="reveal reveal-right max-w-3xl text-center is-visible">
                <p class="mb-6 inline-flex items-center justify-center gap-3 text-xs font-bold uppercase tracking-[0.3em] text-amber-300">
                    <span class="h-0.5 w-10 bg-amber-300"></span>
                    SMK Negeri 1 Bangsri
                    <span class="h-0.5 w-10 bg-amber-300"></span>
                </p>
                <h1 class="text-5xl font-extrabold leading-tight sm:text-6xl lg:text-7xl">
                    Prestasi <span class="bg-gradient-to-r from-amber-300 to-lime-300 bg-clip-text text-transparent">Siswa Terbaik</span> Kami
                </h1>
                <p class="mt-8 mx-auto max-w-2xl text-lg leading-8 text-slate-200 sm:text-xl">Sistem informasi terpusat untuk menampilkan, menganalisis, dan merayakan pencapaian siswa SMK N 1 Bangsri di berbagai bidang kompetisi.</p>

                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('public.prestasi.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 px-5 py-3 text-sm font-bold leading-5 text-slate-950 shadow-2xl shadow-amber-500/30 transition-smooth hover:from-amber-300 hover:to-amber-400 hover:shadow-amber-500/50 active:scale-95 sm:px-6">
                        <i class="fas fa-trophy"></i> Portal Prestasi
                    </a>
                    <a href="{{ route('public.tentang') }}" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 bg-white/10 px-5 py-3 text-sm font-bold leading-5 text-white backdrop-blur-sm transition-smooth hover:bg-white/20 hover:border-white/50 sm:px-6">
                        <i class="fas fa-info-circle"></i> Tentang Kami
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide Indicators -->
        <div x-show="slides.length > 1" class="absolute bottom-8 left-1/2 z-10 flex -translate-x-1/2 items-center gap-3">
            <template x-for="(slide, index) in slides" :key="`dot-${index}`">
                <button
                    type="button"
                    @click="goTo(index)"
                    :aria-label="`Slide ${index + 1}`"
                    :class="active === index ? 'w-8 bg-amber-300' : 'w-2 bg-white/60 hover:bg-white'"
                    class="h-2 rounded-full transition-all duration-300"
                ></button>
            </template>
        </div>
    </section>

    @push('scripts')
    <script>
        function heroSlideshow(slides) {
            return {
                slides,
                active: 0,
                timer: null,
                start() {
                    if (this.slides.length > 1) this.timer = setInterval(() => this.goTo((this.active + 1) % this.slides.length), 5000);
                },
                goTo(index) {
                    this.active = index;
                },
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cards = [...document.querySelectorAll('[data-stack-index]')];
            if (cards.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const positions = ['stack-front', 'stack-middle', 'stack-back', 'stack-rear'];
            let offset = 0;

            setInterval(() => {
                offset = (offset + 1) % cards.length;
                cards.forEach((card, index) => {
                    positions.forEach((position) => card.classList.remove(position));
                    card.classList.add(positions[(index - offset + cards.length) % cards.length]);
                });
            }, 3000);
        });
    </script>
    @endpush

    <!-- Services Cards Section -->
    <section class="relative z-10 mx-auto -mt-16 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($services as $index => $service)
                <div class="reveal reveal-left rounded-2xl border-2 border-slate-100 bg-white p-6 shadow-lg shadow-slate-900/10 transition-smooth hover:-translate-y-2 hover:shadow-xl dark:border-slate-700 dark:bg-slate-900" style="--reveal-delay: {{ $index * 100 }}ms">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border {{ $service['class'] }} text-lg">
                            <i class="fas {{ $service['icon'] }}"></i>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold text-slate-900 dark:text-white">{{ $service['title'] }}</h2>
                            <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-400">{{ $service['text'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- About Section -->
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <!-- Image Collage -->
            <div class="reveal reveal-left order-last lg:order-first">
                <div class="stack-gallery relative mx-auto aspect-[4/5] h-auto w-full max-w-[520px] sm:aspect-auto sm:h-[480px]" aria-label="Kolase dokumentasi prestasi">
                    <div class="stack-gallery-track absolute inset-0">
                        @foreach([
                            $galleryImageOne,
                            $galleryImageTwo,
                            $galleryImageThree,
                            $galleryImageFour,
                        ] as $imageIndex => $galleryImage)
                            <div class="stack-gallery-card {{ ['stack-front', 'stack-middle', 'stack-back', 'stack-rear'][$imageIndex] }}" data-stack-index="{{ $imageIndex }}">
                                <div class="h-full w-full overflow-hidden rounded-[1.75rem] border-4 border-white bg-slate-100 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:rounded-[2rem]">
                                    <img
                                        src="{{ $galleryImage?->foto ? asset('storage/' . $galleryImage->foto) : $heroImage }}"
                                        alt="{{ $galleryImage?->nama_lomba ?: 'Dokumentasi prestasi sekolah' }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    >
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Floating Badge -->
                <div class="absolute -bottom-6 -left-4 z-10 flex items-center gap-3 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-500 px-5 py-4 shadow-2xl shadow-amber-500/30 animate-fade-in animate-delay-200">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white/30">
                        <i class="fas fa-trophy text-lg text-slate-950"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-extrabold leading-none text-slate-950">{{ number_format($totalPrestasi ?? 0) }}</p>
                        <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-amber-900">Prestasi Tercatat</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="reveal reveal-right">
                <p class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-amber-600 dark:text-amber-400">
                    <i class="fas fa-graduation-cap"></i> Tentang Sistem
                </p>
                <h2 class="mt-4 text-4xl font-extrabold leading-tight text-slate-900 dark:text-white sm:text-5xl">
                    Sistem Prestasi <span class="bg-gradient-to-r from-blue-600 to-emerald-600 bg-clip-text text-transparent">Menginspirasi</span> Lebih Baik.
                </h2>
                <p class="mt-6 text-base leading-8 text-slate-700 dark:text-slate-300">
                    Setiap penghargaan yang diraih siswa adalah hasil dari dedikasi, ketekunan, dan kerja keras. Sistem ini kami bangun untuk memastikan setiap prestasi tercatat, mudah ditemukan, dan dapat diakses oleh seluruh komunitas sekolah.
                </p>

                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    <div class="reveal reveal-right flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50/70 text-emerald-700 dark:border-emerald-800/60 dark:bg-emerald-950/20 dark:text-emerald-300">
                            <i class="fas fa-star"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Siswa Berprestasi</h3>
                            <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-400">{{ number_format($totalSiswaBerprestasi ?? 0) }} siswa telah tercatat meraih prestasi.</p>
                        </div>
                    </div>
                    <div class="reveal reveal-right flex gap-4" style="--reveal-delay: 120ms">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-200 bg-blue-50/70 text-blue-700 dark:border-blue-800/60 dark:bg-blue-950/20 dark:text-blue-300">
                            <i class="fas fa-users"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Prestasi Tim</h3>
                            <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-400">{{ number_format($totalPrestasiTim ?? 0) }} pencapaian diraih secara berkelompok.</p>
                        </div>
                    </div>
                </div>

                <div class="reveal reveal-right mt-10 flex flex-wrap items-center gap-6" style="--reveal-delay: 180ms">
                    <a href="{{ route('public.tentang') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3 text-sm font-bold leading-5 text-white shadow-lg shadow-blue-600/30 transition-smooth hover:from-blue-700 hover:to-blue-800 hover:shadow-blue-600/50">
                        Pelajari lebih lanjut <i class="fas fa-arrow-right"></i>
                    </a>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-600 text-white">
                            <i class="fas fa-phone"></i>
                        </span>
                        <div class="text-sm">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Hubungi Kami</p>
                            <p class="font-bold text-slate-900 dark:text-white">SMK N 1 Bangsri</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="reveal reveal-left relative overflow-hidden bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 py-16 text-white">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.06)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        </div>
        <div class="relative mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 text-center sm:px-6 lg:grid-cols-4 lg:px-8">
            @foreach([
                ['value' => $totalPrestasi ?? 0, 'label' => 'Total Prestasi', 'icon' => 'fa-trophy'],
                ['value' => $totalSiswaBerprestasi ?? 0, 'label' => 'Siswa Berprestasi', 'icon' => 'fa-star'],
                ['value' => $totalPrestasiTim ?? 0, 'label' => 'Prestasi Tim', 'icon' => 'fa-users'],
                ['value' => $totalSiswaAktif ?? 0, 'label' => 'Data Siswa', 'icon' => 'fa-user-graduate']
            ] as $index => $stat)
                <div class="animate-fade-in" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="flex items-center justify-center h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm mx-auto mb-3">
                        <i class="fas {{ $stat['icon'] }} text-2xl text-amber-300"></i>
                    </div>
                    <p class="text-4xl font-extrabold sm:text-5xl">{{ number_format($stat['value']) }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-wider text-emerald-100">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Latest Achievements Section -->
    <section class="reveal reveal-right mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mb-12 flex flex-col justify-between gap-4 sm:flex-row sm:items-center animate-fade-in">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-600 dark:text-amber-400">Terbaru</p>
                <h2 class="mt-3 text-4xl font-extrabold text-slate-900 dark:text-white">Prestasi Terkini</h2>
                <p class="mt-2 text-slate-600 dark:text-slate-400">Prestasi siswa yang baru saja dipublikasikan di portal.</p>
            </div>
            <a href="{{ route('public.prestasi.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-100 px-4 py-2.5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:hover:bg-emerald-950/60">
                Lihat semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @forelse($prestasiTerbaru ?? [] as $index => $item)
                <article class="group flex h-full animate-fade-in flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-900/5 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-700" style="animation-delay: {{ $index * 100 }}ms">
                    <a href="{{$item->prestasi ? route('public.prestasi.show', $item->public_token) : '#' }}" class="relative block aspect-[4/3] shrink-0 overflow-hidden bg-gradient-to-br from-slate-100 to-emerald-50 dark:from-slate-800 dark:to-slate-900">
                        <img
                            src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('images/logo-smk.png') }}"
                            alt="{{ $item->nama_lomba }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <div class="absolute left-3 top-3 flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-black uppercase tracking-wide text-amber-700 shadow-lg backdrop-blur dark:bg-slate-900/90 dark:text-amber-400">
                            <i class="fas fa-medal"></i>
                            {{ $item->hasil }}
                        </div>
                    </a>
                    <div class="flex min-w-0 flex-1 flex-col p-3.5">
                        <div class="mb-2 flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-1 text-[9px] font-bold uppercase tracking-[0.1em] text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                <i class="fas fa-layer-group"></i>
                                {{ $item->tingkat ?? 'Umum' }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-[9px] font-bold uppercase tracking-[0.1em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                <i class="fas fa-tag"></i>
                                {{ $item->kategori ?? 'Umum' }}
                            </span>
                        </div>

                        <h3 class="min-h-[2.75rem] break-words text-sm font-black leading-5 text-slate-900 transition group-hover:text-emerald-700 sm:text-base dark:text-white dark:group-hover:text-emerald-400">
                            <a href="{{ route('public.prestasi.show', $item->public_token) }}" class="block [overflow-wrap:anywhere]">{{ $item->nama_lomba }}</a>
                        </h3>

                        @if($item->jenis_peserta === 'Tim' && $item->nama_tim)
                            @php $ekstrakurikulerUrl = config('app.ekstrakurikuler.' . $item->nama_tim); @endphp
                            <p class="mt-2 truncate text-sm font-semibold text-blue-700 dark:text-blue-400"><i class="fas fa-users mr-1"></i>@if($ekstrakurikulerUrl)<a href="{{ $ekstrakurikulerUrl }}" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ $item->nama_tim }}</a>@else{{ $item->nama_tim }}@endif</p>
                        @endif

                        @if($item->tanggal_mulai)
                            <p class="mt-2 flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                                <i class="fas fa-calendar text-emerald-600 dark:text-emerald-400"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                            </p>
                        @endif

                        <div class="mt-auto flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3 text-[11px] text-slate-500 dark:border-slate-800 dark:text-slate-400">
                            <span>{{ $item->jenis_peserta ?? 'Individu' }}</span>
                            <a href="{{ route('public.prestasi.show', $item->public_token) }}" class="inline-flex items-center gap-2 font-bold text-emerald-700 transition group-hover:gap-3 dark:text-emerald-400">
                                Detail
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl bg-slate-50 p-12 text-center dark:bg-slate-900/50">
                    <i class="fas fa-inbox text-4xl text-slate-400 dark:text-slate-600 mb-4"></i>
                    <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada prestasi yang dipublikasikan</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Top Active Students Section -->
    <section class="reveal reveal-left bg-white py-16 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-700 dark:text-emerald-400">Siswa aktif</p>
                    <h2 class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl">Siswa berprestasi teratas</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Siswa aktif dengan jumlah prestasi publik terbanyak.</p>
                </div>
                <a href="{{ route('public.siswa.search') }}" class="inline-flex items-center gap-2 self-start rounded-lg bg-emerald-100 px-4 py-2.5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-200 sm:self-auto dark:bg-emerald-950/40 dark:text-emerald-400 dark:hover:bg-emerald-950/60">
                    Lihat semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @forelse($topSiswaAktif ?? [] as $index => $siswa)
                    @php $initials = collect(explode(' ', trim($siswa->nama)))->filter()->map(fn ($word) => strtoupper(substr($word, 0, 1)))->take(2)->join(''); @endphp
                    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="relative h-14 bg-emerald-700 dark:bg-emerald-800">
                            <div class="absolute -bottom-7 left-4 flex h-14 w-14 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-emerald-100 text-base font-black text-emerald-700 shadow-md dark:border-slate-900 dark:bg-emerald-950 dark:text-emerald-300">
                                @if($siswa->foto)
                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="h-full w-full object-cover">
                                @else
                                    {{ $initials }}
                                @endif
                            </div>
                            <span class="absolute right-3 top-3 rounded-full bg-white/95 px-2 py-1 text-[10px] font-black text-emerald-700 shadow-sm dark:bg-slate-900/90 dark:text-emerald-300">#{{ $index + 1 }}</span>
                        </div>
                        <div class="flex flex-1 flex-col p-4 pt-9">
                            <h3 class="break-words text-sm font-black text-slate-900 dark:text-white">{{ $siswa->nama }}</h3>
                            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">{{ $siswa->kelas ?: '-' }} · {{ $siswa->jurusan ?: '-' }}</p>
                            <dl class="mt-3 grid grid-cols-2 gap-2 border-t border-slate-100 pt-3 text-[10px] dark:border-slate-800">
                                <div><dt class="text-slate-400">Angkatan</dt><dd class="mt-0.5 font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->angkatan ?: '-' }}</dd></div>
                                <div><dt class="text-slate-400">Prestasi</dt><dd class="mt-0.5 font-semibold text-emerald-700 dark:text-emerald-400">{{ $siswa->prestasi_publish_count }}</dd></div>
                            </dl>
                            <div class="mt-auto flex items-center justify-between gap-2 border-t border-slate-100 pt-3 dark:border-slate-800">
                                <span class="text-[10px] font-semibold text-emerald-700 dark:text-emerald-400"><i class="fas fa-trophy mr-1"></i>Aktif</span>
                                <a href="{{ route('public.siswa.profile', ['nama' => \Illuminate\Support\Str::slug($siswa->nama)]) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Profil <i class="fas fa-arrow-right ml-1 text-[10px]"></i></a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-10 text-center dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Belum ada data siswa aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="reveal reveal-right bg-gradient-to-b from-emerald-50/70 to-blue-50/40 py-16 dark:from-emerald-950/20 dark:to-blue-950/20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex items-end justify-between gap-4 animate-fade-in">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-700 dark:text-emerald-400">Dokumentasi</p>
                    <h2 class="mt-3 text-4xl font-extrabold text-slate-900 dark:text-white">Galeri Prestasi</h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">Momen-momen penting pencapaian siswa SMK N 1 Bangsri.</p>
                </div>
                <a href="{{ route('public.galeri.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700 shadow-lg shadow-emerald-600/30">
                    <i class="fas fa-images"></i> Buka Galeri
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                @forelse($galeriPrestasi ?? [] as $index => $item)
                    <a href="{{$item->prestasi ? route('public.prestasi.show', $item->prestasi->public_token):'#'}}" class="group relative aspect-square overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-100 to-emerald-200 shadow-sm transition-smooth hover:-translate-y-1 hover:shadow-lg dark:from-emerald-950/50 dark:to-emerald-950/30 animate-fade-in" style="animation-delay: {{ $index * 50 }}ms">
                        <img
                            src="{{ asset('storage/' . $item->foto) }}"
                            alt="{{ $item->judul ?: 'Foto galeri' }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end">
                            <p class="px-4 pb-3 pt-8 text-xs font-bold text-white line-clamp-2">{{ $item->judul ?: 'Dokumentasi galeri' }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-2xl bg-white p-12 text-center dark:bg-slate-900">
                        <i class="fas fa-image text-4xl text-slate-400 dark:text-slate-600 mb-4"></i>
                        <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada dokumentasi foto</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="reveal reveal-left relative overflow-hidden bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 py-20 text-white">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.06)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        </div>
        <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-lime-300">Jelajahi sekarang</p>
            <h2 class="mt-4 text-4xl font-extrabold sm:text-5xl">Portal Prestasi SMK N 1 Bangsri</h2>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-emerald-100">Akses lengkap untuk melihat semua prestasi, profil siswa, data terukur, dan dokumentasi pencapaian terbaik dari sekolah kami.</p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('public.prestasi.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-lime-300 px-5 py-3 text-sm font-bold leading-5 text-emerald-950 shadow-2xl shadow-lime-300/40 transition-smooth hover:bg-lime-200 hover:shadow-lime-300/60 active:scale-95 sm:px-6">
                    <i class="fas fa-arrow-right"></i> Buka Portal Prestasi
                </a>
                <a href="{{ route('public.tentang') }}" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 px-5 py-3 text-sm font-bold leading-5 text-white backdrop-blur-sm transition-smooth hover:bg-white/20 hover:border-white/50 sm:px-6">
                    <i class="fas fa-book"></i> Panduan
                </a>
            </div>
        </div>
    </section>
</div>
@endsection