@extends('layouts.admin')

@section('title', 'Tambah API Client')

@section('content')
    <div class="page-shell">
        <div class="page-header animate-fade-in">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-200">Integrasi</p>
                    <h1 class="text-2xl font-bold md:text-3xl">Tambah API Client</h1>
                    <p class="mt-2 text-sm">Tambahkan website yang akan menggunakan API SiPres.</p>
                </div>
                <a href="{{ route('admin.api-client.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-white/25">
                    <i class="fas fa-arrow-left"></i>Kembali
                </a>
            </div>
        </div>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Informasi Website
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Masukkan nama website yang akan diberikan akses API.
                        </p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.api-client.store') }}"
                    >

                        @csrf

                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Nama Website
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Website MPLB"
                                required
                                autofocus
                                class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <p class="mt-2 text-xs text-gray-500">
                                Nama ini digunakan untuk mengidentifikasi website
                                pada dashboard API Client.
                            </p>
                        </div>

                        <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4">

                            <div class="text-sm font-semibold text-blue-800">
                                Informasi keamanan
                            </div>

                            <p class="mt-1 text-sm text-blue-700">
                                API key akan dibuat otomatis oleh sistem dan
                                ditampilkan setelah client berhasil dibuat.
                            </p>

                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">

                            <a
                                href="{{ route('admin.api-client.index') }}"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800"
                            >
                                Buat API Client
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

@endsection