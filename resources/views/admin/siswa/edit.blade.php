@extends('layouts.admin')

@section('title', 'Edit Siswa')

@section('content')
<div class="page-shell">
    <div class="page-header animate-fade-in">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.siswa.index') }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Kembali ke daftar siswa">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-blue-500 dark:text-blue-400">Master Data · Siswa</p>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white md:text-3xl">Edit Siswa</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">Perbarui data {{ Str::limit($siswa->nama, 60) }}.</p>
            </div>
        </div>
    </div>

    <div class="admin-form-bubble animate-fade-in p-6 md:p-8">
        <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}" enctype="multipart/form-data" data-ajax-form>
            @csrf
            @method('PUT')
            @include('admin.siswa._form')
        </form>
    </div>
</div>
@endsection
