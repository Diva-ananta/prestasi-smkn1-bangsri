@extends('layouts.admin')

@section('title', 'API Client')

@section('content')
    <div class="page-shell">
        <div class="page-header animate-fade-in">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-200">Integrasi</p>
                    <h1 class="text-2xl font-bold md:text-3xl">API Client</h1>
                    <p class="mt-2 text-sm">Kelola website yang memiliki akses ke API SiPres.</p>
                </div>
                <a href="{{ route('admin.api-client.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-lime-400 px-4 py-2.5 text-xs font-bold text-slate-900 shadow-lg shadow-emerald-950/20 transition hover:bg-lime-300">
                    <i class="fas fa-plus"></i>Tambah API Client
                </a>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Total Client</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($statistics['total_clients']) }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Client Aktif</p>
            <p class="mt-2 text-3xl font-semibold text-green-600">{{ number_format($statistics['active_clients']) }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Total Request</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($statistics['total_requests']) }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium text-gray-500">Request Hari Ini</p>
            <p class="mt-2 text-3xl font-semibold text-blue-600">{{ number_format($statistics['requests_today']) }}</p>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">
                                        Website
                                    </th>

                                    <th class="px-4 py-3">
                                        Status
                                    </th>

                                    <th class="px-4 py-3">
                                        Total Request
                                    </th>

                                    <th class="px-4 py-3">
                                        Terakhir Digunakan
                                    </th>

                                    <th class="px-4 py-3 text-right">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @forelse ($clients as $client)

                                    <tr>

                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900">
                                                {{ $client->name }}
                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">
                                                Dibuat {{ $client->created_at?->format('d M Y') }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">

                                            @if ($client->is_active)

                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                                    Aktif
                                                </span>

                                            @else

                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">
                                                    Nonaktif
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            {{ number_format($client->usage_logs_count) }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-700">

                                            @if ($client->last_used_at)

                                                {{ $client->last_used_at->format('d M Y H:i') }}

                                            @else

                                                <span class="text-gray-400">
                                                    Belum digunakan
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-4 py-4 text-right">

                                            <div class="flex items-center justify-end gap-2">

                                                    <a
                                                        href="{{ route('admin.api-client.show', $client) }}"
                                                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                                    >
                                                        Lihat
                                                    </a>

                                                    <form
                                                        action="{{ route('admin.api-client.toggle-status', $client) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ $client->is_active ? 'Nonaktifkan API Client ini?' : 'Aktifkan kembali API Client ini?' }}')"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        @if ($client->is_active)
                                                            <button
                                                                type="submit"
                                                                class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100"
                                                            >
                                                                Nonaktifkan
                                                            </button>
                                                        @else
                                                            <button
                                                                type="submit"
                                                                class="inline-flex items-center rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100"
                                                            >
                                                                Aktifkan
                                                            </button>
                                                        @endif

                                                    </form>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="5"
                                            class="px-4 py-8 text-center text-sm text-gray-500"
                                        >
                                            Belum ada API Client.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>
                    </div>

                    @if ($clients->hasPages())

                        <div class="mt-6">
                            {{ $clients->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>
@endsection
