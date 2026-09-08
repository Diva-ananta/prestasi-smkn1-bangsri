@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')
<div class="page-shell">
    <div class="page-header animate-fade-in">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-blue-500 dark:text-blue-400">Master Data</p>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white md:text-3xl">Data Siswa</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Kelola data siswa SMK N 1 Bangsri.</p>
            </div>
            <a href="{{ route('admin.siswa.create') }}" class="admin-btn-primary gap-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Siswa</span>
            </a>
        </div>
    </div>

    <div class="section-card animate-fade-in">
        <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col gap-3 md:flex-row md:items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, atau NISN..." class="admin-form-input md:flex-1">
            <button type="submit" class="admin-btn-primary">Cari</button>
            @if(request('search'))
                <a href="{{ route('admin.siswa.index') }}" class="admin-btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="section-card animate-fade-in">
        <x-admin.table class="admin-table">
            <thead>
                <tr>
                    <th><input type="checkbox" onclick="document.querySelectorAll('.siswa-select').forEach((item) => item.checked = this.checked)" aria-label="Pilih semua"></th><th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Angkatan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr>
                        <td><input type="checkbox" class="siswa-select" value="{{ $siswa->id }}" aria-label="Pilih {{ $siswa->nama }}"></td><td>{{ $loop->iteration }}</td>
                        <td class="font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->nis }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td>{{ $siswa->kelas }}</td>
                        <td>{{ $siswa->jurusan }}</td>
                        <td>{{ $siswa->angkatan }}</td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a data-ajax-page href="{{ route('admin.siswa.show', $siswa) }}" class="rounded-xl bg-blue-100 px-2.5 py-2 text-blue-600 transition hover:bg-blue-200 dark:bg-blue-900/40 dark:text-blue-300" title="Detail"><i class="fas fa-eye"></i></a>
                                <a data-ajax-page href="{{ route('admin.siswa.edit', $siswa) }}" class="rounded-xl bg-amber-100 px-2.5 py-2 text-amber-600 transition hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-300" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-xl bg-red-100 px-2.5 py-2 text-red-600 transition hover:bg-red-200 dark:bg-red-900/40 dark:text-red-300" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">Belum ada data siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>

    <div class="animate-fade-in">
        {{ $siswas->links() }}
    </div>
</div>
@endsection