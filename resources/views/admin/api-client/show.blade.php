@extends('layouts.admin')

@section('title', 'Detail API Client')

@section('content')
    <div class="page-shell">
        <div class="page-header animate-fade-in">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-200">Integrasi</p>
                    <h1 class="text-2xl font-bold md:text-3xl">Detail API Client</h1>
                    <p class="mt-2 text-sm">Pantau kredensial dan aktivitas penggunaan API client.</p>
                </div>
                <a href="{{ route('admin.api-client.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-white/25">
                    <i class="fas fa-arrow-left"></i>Kembali
                </a>
            </div>
        </div>

    <div class="py-6">
        @if (session('api_key'))
        <div class="mb-6 rounded-xl border border-yellow-300 bg-yellow-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex-1">
                    <h3 class="font-semibold text-yellow-900">API Client berhasil dibuat</h3>
                    <p class="mt-1 text-sm text-yellow-800">Simpan API key berikut sekarang. Demi keamanan, key ini hanya ditampilkan pada saat pembuatan client.</p>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-yellow-800">
                            API Key
                        </label>

                        <div class="mt-2 rounded-lg border border-yellow-300 bg-white p-3">
                            <code class="break-all text-sm text-gray-900">
                                {{ session('api_key') }}
                            </code>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Informasi Client --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-600">
                                API Client
                            </div>

                            <h1 class="mt-2 text-2xl font-bold text-gray-900">
                                {{ $apiClient->name }}
                            </h1>

                            <p class="mt-1 text-sm text-gray-500">
                                Client yang terdaftar untuk mengakses API SiPres.
                            </p>
                        </div>

                        <div>
                            @if ($apiClient->is_active)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700">
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="text-sm text-gray-500">
                                Total Request
                            </div>

                            <div class="mt-1 text-2xl font-bold text-gray-900">
                                {{ number_format($apiClient->usageLogs()->count()) }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="text-sm text-gray-500">
                                Terakhir Digunakan
                            </div>

                            <div class="mt-1 text-sm font-semibold text-gray-900">
                                @if ($apiClient->last_used_at)
                                    {{ $apiClient->last_used_at->format('d M Y H:i') }}
                                @else
                                    <span class="text-gray-400">
                                        Belum digunakan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="text-sm text-gray-500">
                                Terdaftar Sejak
                            </div>

                            <div class="mt-1 text-sm font-semibold text-gray-900">
                                {{ $apiClient->created_at?->format('d M Y H:i') }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            {{-- Riwayat Request --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="mb-5">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Riwayat Request
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Daftar aktivitas request yang dilakukan oleh client ini.
                        </p>
                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                    <th class="px-4 py-3">
                                        Waktu
                                    </th>

                                    <th class="px-4 py-3">
                                        Method
                                    </th>

                                    <th class="px-4 py-3">
                                        Endpoint
                                    </th>

                                    <th class="px-4 py-3">
                                        Status
                                    </th>

                                    <th class="px-4 py-3">
                                        Response
                                    </th>

                                    <th class="px-4 py-3">
                                        IP
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @forelse ($usageLogs as $log)

                                    <tr>

                                        <td class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            {{ $log->created_at?->format('d M Y H:i:s') }}
                                        </td>

                                        <td class="px-4 py-4">

                                            @if ($log->method === 'GET')

                                                <span class="inline-flex rounded-md bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                    GET
                                                </span>

                                            @elseif ($log->method === 'POST')

                                                <span class="inline-flex rounded-md bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                    POST
                                                </span>

                                            @elseif ($log->method === 'PUT' || $log->method === 'PATCH')

                                                <span class="inline-flex rounded-md bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                                    {{ $log->method }}
                                                </span>

                                            @elseif ($log->method === 'DELETE')

                                                <span class="inline-flex rounded-md bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                    DELETE
                                                </span>

                                            @else

                                                <span class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                                    {{ $log->method }}
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-4 py-4">
                                            <code class="text-sm text-gray-700">
                                                {{ $log->endpoint }}
                                            </code>
                                        </td>

                                        <td class="px-4 py-4">

                                            @if ($log->status_code >= 200 && $log->status_code < 300)

                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                    {{ $log->status_code }}
                                                </span>

                                            @elseif ($log->status_code >= 400)

                                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                    {{ $log->status_code }}
                                                </span>

                                            @else

                                                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                                    {{ $log->status_code ?? '-' }}
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            @if ($log->response_time_ms !== null)
                                                {{ number_format($log->response_time_ms) }} ms
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $log->ip_address ?? '-' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="6"
                                            class="px-4 py-10 text-center"
                                        >
                                            <div class="text-sm text-gray-500">
                                                Belum ada riwayat request.
                                            </div>
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    @if ($usageLogs->hasPages())

                        <div class="mt-6">
                            {{ $usageLogs->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>

    </div>
@endsection