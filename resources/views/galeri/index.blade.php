@extends('layouts.public')

@section('title', 'Galeri Prestasi - SMK N 1 Bangsri')

@section('content')

<div class="min-h-screen bg-[#f6faf7] dark:bg-slate-950">

    {{-- HEADER --}}
    <section class="border-b border-emerald-100 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 pb-10 pt-12 text-center sm:px-6 lg:px-8 lg:pb-14 lg:pt-16">

            <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-700 dark:text-emerald-400">
                Dokumentasi sekolah
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-5xl">
                <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent dark:from-emerald-400 dark:to-blue-400">Galeri Prestasi</span>
            </h1>

            <p class="page-subtitle mx-auto mt-4 max-w-xl text-sm leading-7 sm:text-base">
                Kumpulan dokumentasi pencapaian siswa SMK Negeri 1 Bangsri.
            </p>

            <div class="mt-6 flex flex-wrap justify-center gap-2">
                <button type="button" @click="activeCatalog = 'all'" :class="activeCatalog === 'all' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300'" class="rounded-full border border-emerald-200 px-4 py-2 text-sm font-bold transition dark:border-slate-700">Semua</button>
                <button type="button" @click="activeCatalog = 'youtube'" :class="activeCatalog === 'youtube' ? 'bg-red-600 text-white' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300'" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-bold transition dark:border-slate-700"><i class="fab fa-youtube mr-1.5"></i>YouTube</button>
                <button type="button" @click="activeCatalog = 'tiktok'" :class="activeCatalog === 'tiktok' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300'" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-bold transition dark:border-slate-700"><i class="fab fa-tiktok mr-1.5"></i>TikTok</button>
                <button type="button" @click="activeCatalog = 'instagram'" :class="activeCatalog === 'instagram' ? 'bg-pink-600 text-white' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300'" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-bold transition dark:border-slate-700"><i class="fab fa-instagram mr-1.5"></i>Instagram</button>
                <button type="button" @click="activeCatalog = 'foto'" :class="activeCatalog === 'foto' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300'" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-bold transition dark:border-slate-700"><i class="fas fa-image mr-1.5"></i>Foto</button>
            </div>

        </div>
    </section>


    {{-- GALERI --}}
    <section x-data="{ activeCatalog: 'all' }" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

        {{-- GRID KATALOG --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

            @forelse($galeri as $item)

                @php($videoUrl = $item->video_url ?: $item->prestasi?->video_url)
                @php($platform = \App\Helpers\SocialMedia::platform($videoUrl))
                @php($embedUrl = \App\Helpers\SocialMedia::embed($videoUrl))
                <div x-show="activeCatalog === 'all' || activeCatalog === '{{ $platform ?: 'foto' }}'" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

                    <div class="relative aspect-[4/5] overflow-hidden bg-emerald-50 dark:bg-emerald-950">
                        @if($embedUrl && $platform === 'youtube')
                            <details class="group/video h-full">
                                <summary class="relative h-full cursor-pointer list-none">
                                    <img src="{{ \App\Helpers\YouTube::thumbnail($videoUrl) }}" alt="Thumbnail video {{ $item->judul ?: 'galeri' }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                                    <span class="absolute inset-0 flex items-center justify-center bg-slate-950/20 text-white"><span class="flex h-14 w-14 items-center justify-center rounded-full bg-red-600 shadow-lg"><i class="fas fa-play"></i></span></span>
                                </summary>
                                <iframe src="{{ \App\Helpers\YouTube::embed($videoUrl) }}" title="{{ $item->judul ?: 'Video galeri' }}" class="absolute inset-0 h-full w-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </details>
                        @elseif($embedUrl)
                            <iframe src="{{ $embedUrl }}" title="{{ $item->judul ?: 'Konten ' . ucfirst($platform) }}" class="absolute inset-0 h-full w-full border-0" loading="lazy" allow="encrypted-media; picture-in-picture; web-share" allowfullscreen></iframe>
                        @elseif($item->prestasi?->foto || $item->foto)
                            <img src="{{ asset('storage/' . ($item->prestasi?->foto ?: $item->foto)) }}" alt="{{ $item->judul ?: $item->prestasi?->nama_lomba ?: 'Foto galeri' }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        @endif

                    </div>

                    <div class="flex items-center gap-2 p-3">
                        @if($platform)<i class="fab fa-{{ $platform === 'youtube' ? 'youtube text-red-600' : ($platform === 'instagram' ? 'instagram text-pink-600' : 'tiktok') }}"></i>@endif
                        <p class="truncate text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $item->judul ?: $item->prestasi?->nama_lomba ?: ucfirst($platform ?: 'Foto galeri') }}</p>
                    </div>

                </div>

            @empty

                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center dark:border-slate-700 dark:bg-slate-900">

                    <i class="fas fa-images text-4xl text-slate-400 dark:text-slate-600"></i>

                <p class="mt-4 font-semibold text-slate-600 dark:text-slate-400">
                        Belum ada dokumentasi yang dipublikasikan.
                    </p>

                </div>

            @endforelse

        </div>


        {{-- PAGINATION --}}
        <div class="mt-10">
            {{ $galeri->links() }}
        </div>

    </section>

</div>

@endsection
