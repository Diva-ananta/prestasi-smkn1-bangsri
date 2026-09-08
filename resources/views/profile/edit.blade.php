@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="page-shell max-w-4xl">
    <div class="page-header animate-fade-in">
        <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-500 dark:text-emerald-400">Pengaturan akun</p>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white md:text-3xl">Profil Admin</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Perbarui nama, email, dan password akun admin Anda.</p>
    </div>

    <div class="space-y-6">
        <div class="section-card animate-fade-in">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="section-card animate-fade-in">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</div>
@endsection
