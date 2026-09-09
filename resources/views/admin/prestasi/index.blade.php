@extends('layouts.admin')

@section('title', 'Data Prestasi')

@section('content')
<div class="page-shell">
    <div class="page-header animate-fade-in">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-blue-500 dark:text-blue-400">Master Data</p>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white md:text-3xl">Data Prestasi</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Kelola semua prestasi dan penempatan anggota dengan mudah.</p>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:flex-wrap sm:gap-3">
                <div class="grid w-full grid-cols-2 gap-2 sm:flex sm:w-auto" data-export-controls="prestasi">
                    <button type="button" onclick="togglePrestasiExportMode()" class="admin-btn-secondary min-w-0 w-full sm:w-auto" data-export-start><i class="fas fa-file-export mr-2"></i>Export</button>
                    <button type="button" onclick="exportSelectedPrestasi('{{ route('admin.prestasi.export') }}')" class="admin-btn-primary col-span-2 hidden min-w-0 w-full sm:w-auto" data-export-download><i class="fas fa-download mr-2"></i>Download</button>
                </div>
                <a href="{{ route('admin.prestasi.import') }}" class="admin-btn-secondary w-full sm:w-auto"><i class="fas fa-file-import mr-2"></i>Import Excel</a>
                <button type="button" onclick="openPrestasiModal()" class="admin-btn-primary w-full sm:w-auto"><i class="fas fa-plus mr-2"></i>Tambah Prestasi</button>
            </div>
        </div>
    </div>

    <div id="prestasi-modal" class="{{ $errors->any() ? 'flex' : 'hidden' }} fixed inset-0 z-[60] items-start justify-center overflow-y-auto bg-slate-950/60 px-4 py-6 backdrop-blur-sm sm:py-10" role="dialog" aria-modal="true" aria-labelledby="prestasi-form-title">
        <div class="admin-form-bubble relative my-auto w-full max-w-4xl max-h-[calc(100vh-3rem)] overflow-y-auto p-6 md:p-8">
            <div class="flex items-center justify-between">
                <h3 id="prestasi-form-title" class="text-lg font-bold">Tambah Prestasi</h3>
                <button type="button" onclick="closePrestasiModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup form"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="mt-4" data-ajax-form>
                @csrf
                @php $selectedSiswa = old('siswa_id', []); @endphp
                @include('admin.prestasi._form')
            </form>
        </div>
    </div>

    <div class="section-card animate-fade-in">
        <form action="{{ route('admin.prestasi.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="Cari nama lomba, jenis peserta, hasil, atau kategori" class="admin-form-input md:flex-1">
            <button type="submit" class="admin-btn-primary w-full sm:w-auto">Cari</button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.prestasi.index') }}" class="admin-btn-secondary w-full sm:w-auto">Reset</a>
            @endif
        </form>
    </div>

    <div class="section-card animate-fade-in">
        <x-admin.table class="admin-table admin-table-mobile-cards">
            <thead>
                <tr>
                    <th data-export-column class="hidden"><input type="checkbox" id="select-all-prestasi" onclick="document.querySelectorAll('.prestasi-select').forEach((item) => item.checked = this.checked)" aria-label="Pilih semua"></th><th>No</th>
                    <th>Nama Lomba</th>
                    <th>Jenis Peserta</th>
                    <th>Hasil</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasis as $prestasi)
                    <tr>
                        <td data-export-column class="hidden"><input type="checkbox" class="prestasi-select" value="{{ $prestasi->id }}" aria-label="Pilih {{ $prestasi->nama_lomba }}"></td><td>{{ $prestasis->firstItem() + $loop->index }}</td>
                        <td data-label="Nama lomba" class="font-semibold text-slate-700 dark:text-slate-200">{{ $prestasi->nama_lomba }}</td>
                        <td data-label="Jenis peserta">{{ $prestasi->jenis_peserta }}</td>
                        <td data-label="Hasil">{{ $prestasi->hasil }}</td>
                        <td data-label="Status">
                            <span class="admin-badge {{ $prestasi->status == 'Publish' ? 'success' : 'warning' }}">
                                {{ $prestasi->status }}
                            </span>
                        </td>
                        <td data-label="Aksi">
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('admin.prestasi.review', $prestasi) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $prestasi->status === 'Publish' ? 'Draft' : 'Publish' }}">
                                    <button type="submit" title="{{ $prestasi->status === 'Publish' ? 'Kembalikan ke draft' : 'Review dan publikasikan' }}" aria-label="{{ $prestasi->status === 'Publish' ? 'Kembalikan ke draft' : 'Review dan publikasikan' }}" class="flex h-9 w-9 items-center justify-center rounded-xl {{ $prestasi->status === 'Publish' ? 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300' }}" onclick="return confirm('{{ $prestasi->status === 'Publish' ? 'Kembalikan prestasi menjadi draft?' : 'Setujui dan publikasikan prestasi ini?' }}')"><i class="fas {{ $prestasi->status === 'Publish' ? 'fa-eye-slash' : 'fa-check' }}"></i></button>
                                </form>
                                <a data-ajax-page href="{{ route('admin.prestasi.edit', $prestasi) }}" title="Edit prestasi" aria-label="Edit prestasi" class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700 transition hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-300"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.prestasi.destroy', $prestasi) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus prestasi" aria-label="Hapus prestasi" class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-700 transition hover:bg-red-200 dark:bg-red-900/40 dark:text-red-300"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">Belum ada data prestasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>

    <div class="animate-fade-in">
        {{ $prestasis->links() }}
    </div>
</div>
<script>
    function openPrestasiModal() {
        const modal = document.getElementById('prestasi-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        document.getElementById('nama_lomba')?.focus();
    }

    function closePrestasiModal() {
        const modal = document.getElementById('prestasi-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('prestasi-modal')?.addEventListener('click', function (event) {
        if (event.target === this) closePrestasiModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closePrestasiModal();
    });

    @if($errors->any()) document.body.classList.add('overflow-hidden'); @endif

    function exportSelectedPrestasi(url) {
        const ids = [...document.querySelectorAll('.prestasi-select:checked')].map((item) => `ids[]=${encodeURIComponent(item.value)}`);
        if (!ids.length) return window.adminNotify?.('Pilih minimal satu data untuk diekspor.', 'error');
        window.location.href = `${url}?${ids.join('&')}`;
    }

    function togglePrestasiExportMode() {
        const active = !document.querySelector('.admin-table-mobile-cards')?.classList.contains('export-mode');
        document.querySelector('.admin-table-mobile-cards')?.classList.toggle('export-mode', active);
        document.querySelector('[data-export-controls="prestasi"] [data-export-start]')?.classList.toggle('bg-amber-100', active);
        document.querySelector('[data-export-controls="prestasi"] [data-export-start]')?.classList.toggle('text-amber-800', active);
        document.querySelector('[data-export-controls="prestasi"] [data-export-download]')?.classList.toggle('hidden', !active);
        document.querySelectorAll('[data-export-column]').forEach((element) => element.classList.toggle('hidden', !active));
        if (!active) document.querySelectorAll('.prestasi-select, #select-all-prestasi').forEach((item) => item.checked = false);
    }
</script>
@endsection