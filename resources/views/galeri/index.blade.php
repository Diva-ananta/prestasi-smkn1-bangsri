@extends('layouts.public')

@section('title', 'Galeri Prestasi - SMK N 1 Bangsri')

@section('content')

<div class="min-h-screen bg-[#f6faf7] dark:bg-slate-950">

    {{-- HEADER --}}
    <section class="border-b border-emerald-100 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 pb-10 pt-12 sm:px-6 lg:px-8 lg:pb-14 lg:pt-16">

            <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-700 dark:text-emerald-400">
                Dokumentasi sekolah
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-5xl">
                <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent dark:from-emerald-400 dark:to-blue-400">Galeri Prestasi</span>
            </h1>

            <p class="page-subtitle mt-4 max-w-xl text-sm leading-7 sm:text-base">
                Kumpulan dokumentasi pencapaian siswa SMK Negeri 1 Bangsri.
            </p>

        </div>
    </section>


    {{-- GALERI --}}
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

        <div class="mb-7">

            <h2 class="page-title text-xl font-bold">
                Koleksi dokumentasi
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ number_format($galeri->total()) }} foto prestasi tersedia.
            </p>

        </div>


        {{-- GRID FOTO --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

            @forelse($galeri as $item)

                {{-- Tidak menggunakan <a>, sehingga foto tidak menuju detail --}}
                <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

                    <div class="relative aspect-[4/3] overflow-hidden bg-emerald-50 dark:bg-emerald-950">

                        <img
                            src="{{ asset('storage/' . $item->foto) }}"
                            alt="{{ $item->judul ?: 'Foto galeri' }}"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            loading="lazy"
                        >

                    </div>

                </div>

            @empty

                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center dark:border-slate-700 dark:bg-slate-900">

                    <i class="fas fa-images text-4xl text-slate-400 dark:text-slate-600"></i>

                    <p class="mt-4 font-semibold text-slate-600 dark:text-slate-400">
                        Belum ada foto prestasi yang dipublikasikan.
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