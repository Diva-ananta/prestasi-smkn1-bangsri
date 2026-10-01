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
            <div class="flex w-full min-w-0 flex-wrap gap-2 sm:w-auto sm:justify-end sm:gap-3" data-export-controls="prestasi">
                <button type="button" onclick="togglePrestasiExportMode()" class="admin-btn-secondary min-h-11 min-w-0 flex-1 basis-[calc(50%-0.25rem)] justify-center px-2 text-center text-[11px] leading-tight sm:flex-none sm:basis-auto sm:whitespace-nowrap sm:px-3 sm:text-sm" data-export-start><i class="fas fa-file-export mr-1 shrink-0 sm:mr-2"></i><span data-export-start-label>Export Excel</span></button>
                <button type="button" onclick="exportSelectedPrestasi('{{ route('admin.prestasi.export') }}')" class="admin-btn-primary min-h-11 min-w-0 flex-1 basis-[calc(50%-0.25rem)] justify-center px-2 text-center text-[11px] leading-tight sm:flex-none sm:basis-auto sm:whitespace-nowrap sm:px-3 sm:text-sm" data-export-download hidden style="display: none;"><i class="fas fa-download mr-1 shrink-0 sm:mr-2"></i>Download pilihan</button>
                <button type="button" onclick="exportAllPrestasi('{{ route('admin.prestasi.export') }}')" class="admin-btn-secondary min-h-11 min-w-0 flex-1 basis-[calc(50%-0.25rem)] justify-center px-2 text-center text-[11px] leading-tight sm:flex-none sm:basis-auto sm:whitespace-nowrap sm:px-3 sm:text-sm" data-export-all hidden style="display: none;"><i class="fas fa-download mr-1 shrink-0 sm:mr-2"></i>Download semua hasil</button>
                <a href="{{ route('admin.prestasi.import') }}" class="admin-btn-secondary min-h-11 min-w-0 flex-1 basis-[calc(50%-0.25rem)] justify-center px-2 text-center text-[11px] leading-tight sm:flex-none sm:basis-auto sm:whitespace-nowrap sm:px-3 sm:text-sm"><i class="fas fa-file-import mr-1 shrink-0 sm:mr-2"></i>Import Excel</a>
                <button type="button" onclick="openPrestasiModal()" class="admin-btn-primary min-h-11 min-w-0 flex-1 basis-full justify-center px-2 text-center text-[11px] leading-tight sm:flex-none sm:basis-auto sm:whitespace-nowrap sm:px-3 sm:text-sm"><i class="fas fa-plus mr-1 shrink-0 sm:mr-2"></i>Tambah Prestasi</button>
            </div>
        </div>
    </div>

    <div id="prestasi-modal" class="{{ $errors->any() ? 'flex' : 'hidden' }} fixed inset-0 z-[60] items-start justify-center overflow-y-auto bg-slate-950/60 px-4 py-6 backdrop-blur-sm sm:py-10" role="dialog" aria-modal="true" aria-labelledby="prestasi-form-title">
        <div class="admin-form-bubble relative my-auto w-full max-w-4xl max-h-[calc(100vh-3rem)] overflow-y-auto p-6 md:p-8">
            <div class="flex items-center justify-between">
                <h3 id="prestasi-form-title" class="text-lg font-bold">Tambah Prestasi</h3>
                <button type="button" onclick="closePrestasiModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup form"><i class="fas fa-times"></i></button>
            </div>
            <form form action="{{ route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="mt-4" data-ajax-form>
                @csrf
                @php $selectedSiswa = old('siswa_id', []); @endphp
                @include('admin.prestasi._form')
            </form>
        </div>
    </div>

    <div class="section-card animate-fade-in relative z-30 p-3 sm:p-4">
        <form action="{{ route('admin.prestasi.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end" data-prestasi-filters data-live-search data-ajax-target="#prestasi-results">
            <label class="relative block min-w-0 flex-1">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300">Cari prestasi</span>
                <span class="pointer-events-none absolute bottom-0 left-3 flex h-12 items-center text-slate-400" aria-hidden="true"></span>
                <input name="search" type="search" value="{{ request('search') }}" placeholder="Nama lomba, hasil, kategori..." aria-label="Cari prestasi" class="admin-form-input mt-0 min-w-0 pl-10">
            </label>
            <details class="relative shrink-0">
                <summary class="flex min-h-11 cursor-pointer list-none items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 [&::-webkit-details-marker]:hidden dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">
                    <i class="fas fa-filter text-xs"></i>
                    Filter
                    @if(request()->hasAny(['tahun', 'kategori', 'tingkat', 'jenis_peserta', 'status']))
                        <span class="h-2 w-2 rounded-full bg-blue-500" aria-label="Filter aktif"></span>
                    @endif
                </summary>
                <div class="absolute right-0 top-full z-[70] mt-2 w-[min(90vw,56rem)] rounded-2xl border border-slate-200 bg-white p-4 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                <div class="grid grid-cols-2 gap-x-3 gap-y-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            <label class="block min-w-0">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300">Tahun</span>
                <select name="tahun" class="admin-form-input mt-0 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter tahun">
                    <option value="">Semua tahun</option>
                    @foreach($tahunOptions as $tahun)
                        <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>{{ $tahun }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block min-w-0">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300">Kategori</span>
                <select name="kategori" class="admin-form-input mt-0 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter kategori">
                    <option value="">Semua kategori</option>
                    @foreach($kategoriOptions as $kategori)
                        <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>{{ $kategori }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block min-w-0">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300">Tingkat</span>
                <select name="tingkat" class="admin-form-input mt-0 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter tingkat">
                    <option value="">Semua tingkat</option>
                    @foreach($tingkatOptions as $tingkat)
                        <option value="{{ $tingkat }}" @selected(request('tingkat') === $tingkat)>{{ $tingkat }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block min-w-0">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300">Peserta</span>
                <select name="jenis_peserta" class="admin-form-input mt-0 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter jenis peserta">
                    <option value="">Semua jenis</option>
                    <option value="Individu" @selected(request('jenis_peserta') === 'Individu')>Individu</option>
                    <option value="Tim" @selected(request('jenis_peserta') === 'Tim')>Tim</option>
                </select>
            </label>
            <label class="block min-w-0">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300">Status</span>
                <select name="status" class="admin-form-input mt-0 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter status">
                    <option value="">Semua status</option>
                    <option value="Publish" @selected(request('status') === 'Publish')>Publish</option>
                    <option value="Draft" @selected(request('status') === 'Draft')>Draft</option>
                </select>
            </label>
            <div class="col-span-2 flex items-center justify-end gap-2 border-t border-slate-200 pt-3 sm:col-span-3 lg:col-span-4 xl:col-span-5 dark:border-slate-700">
                @if(request()->hasAny(['search', 'tahun', 'kategori', 'tingkat', 'jenis_peserta', 'status']))
                    <a href="{{ route('admin.prestasi.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">Reset</a>
                @endif
                <button type="submit" class="admin-btn-primary"><i class="fas fa-check mr-2"></i>Terapkan</button>
            </div>
                </div>
            </details>
        </form>
        </div>
    </div>

    <div id="prestasi-results">
    <div class="section-card animate-fade-in">
        <x-admin.table class="admin-table admin-table-mobile-cards min-w-[900px]">
            <thead>
                <tr>
                    <th data-export-column class="hidden"><input type="checkbox" id="select-all-prestasi" onclick="toggleAllPrestasiSelection(this)" aria-label="Pilih semua data di halaman ini"></th>
                    <th class="w-14 text-center">No</th>
                    <th class="w-24">Thumbnail</th>
                    <th class="min-w-[260px]">Nama Lomba</th>
                    <th class="w-36">Jenis Peserta</th>
                    <th class="w-36">Hasil</th>
                    <th class="w-28">Status</th>
                    <th class="w-36 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasis as $prestasi)
                    <tr>
                        <td data-export-column class="hidden"><input type="checkbox" class="prestasi-select" value="{{ $prestasi->id }}" aria-label="Pilih {{ $prestasi->nama_lomba }}"></td>
                        <td data-label="No" class="text-center font-semibold text-slate-500 dark:text-slate-400">{{ $prestasis->firstItem() + $loop->index }}</td>
                        <td data-label="Thumbnail">
                            <div class="flex h-16 w-12 items-center justify-center overflow-hidden rounded-lg border border-slate-200 bg-slate-100 dark:border-slate-700 dark:bg-slate-800">
                                @if($prestasi->foto)
                                    <img src="{{ asset('storage/' . $prestasi->foto) }}" alt="Thumbnail {{ $prestasi->nama_lomba }}" class="h-full w-full object-cover" loading="lazy">
                                @else
                                    <i class="fas fa-image text-slate-400 dark:text-slate-500" aria-hidden="true"></i>
                                    <span class="sr-only">Belum ada thumbnail</span>
                                @endif
                            </div>
                        </td>
                        <td data-label="Nama lomba" class="max-w-[320px] font-semibold text-slate-700 dark:text-slate-200"><span class="block truncate" title="{{ $prestasi->nama_lomba }}">{{ $prestasi->nama_lomba }}</span></td>
                        <td data-label="Jenis peserta" class="whitespace-nowrap">{{ $prestasi->jenis_peserta }}</td>
                        <td data-label="Hasil" class="max-w-[160px]"><span class="block truncate" title="{{ $prestasi->hasil }}">{{ $prestasi->hasil }}</span></td>
                        <td data-label="Status" class="whitespace-nowrap">
                            <span class="admin-badge {{ $prestasi->status == 'Publish' ? 'success' : 'warning' }}">
                                {{ $prestasi->status }}
                            </span>
                        </td>
                        <td data-label="Aksi" class="whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('admin.prestasi.review', $prestasi) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $prestasi->status === 'Publish' ? 'Draft' : 'Publish' }}">
                                    <button type="submit" title="{{ $prestasi->status === 'Publish' ? 'Kembalikan ke Draft' : 'Publikasikan prestasi' }}" aria-label="{{ $prestasi->status === 'Publish' ? 'Kembalikan ke Draft' : 'Publikasikan prestasi' }}" class="flex h-9 w-9 items-center justify-center rounded-xl {{ $prestasi->status === 'Publish' ? 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300' }}" onclick="return confirm('{{ $prestasi->status === 'Publish' ? 'Kembalikan prestasi menjadi Draft?' : 'Publikasikan prestasi ini?' }}')"><i class="fas {{ $prestasi->status === 'Publish' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i></button>
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
                        <td colspan="8" class="py-12 text-center text-slate-400">Belum ada data prestasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>

    <div class="animate-fade-in">
        {{ $prestasis->links() }}
    </div>
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

    function toggleAllPrestasiSelection(checkbox) {
        document.querySelectorAll('.prestasi-select').forEach((item) => item.checked = checkbox.checked);
    }

    function exportSelectedPrestasi(url) {
        const params = new URLSearchParams();
        const selected = document.querySelectorAll('.prestasi-select:checked');
        if (!selected.length) return window.adminNotify?.('Pilih minimal satu data untuk diekspor.', 'error');
        selected.forEach((item) => params.append('ids[]', item.value));

        window.location.href = `${url}?${params.toString()}`;
    }

    function exportAllPrestasi(url) {
        const params = new URLSearchParams(new FormData(document.querySelector('[data-prestasi-filters]')));
        params.set('all', '1');
        window.location.href = `${url}?${params.toString()}`;
    }

    function togglePrestasiExportMode() {
        const active = !document.querySelector('.admin-table-mobile-cards')?.classList.contains('export-mode');
        const startButton = document.querySelector('[data-export-controls="prestasi"] [data-export-start]');
        const startLabel = document.querySelector('[data-export-controls="prestasi"] [data-export-start-label]');
        const downloadButton = document.querySelector('[data-export-controls="prestasi"] [data-export-download]');
        const downloadAllButton = document.querySelector('[data-export-controls="prestasi"] [data-export-all]');
        document.querySelector('.admin-table-mobile-cards')?.classList.toggle('export-mode', active);
        startButton?.classList.toggle('bg-amber-100', active);
        startButton?.classList.toggle('text-amber-800', active);
        startLabel.textContent = active ? 'Batal pilih' : 'Export Excel';
        startButton?.querySelector('i')?.classList.toggle('fa-file-export', !active);
        startButton?.querySelector('i')?.classList.toggle('fa-xmark', active);
        downloadButton.disabled = !active;
        downloadButton.hidden = !active;
        downloadButton.style.display = active ? 'inline-flex' : 'none';
        downloadAllButton.hidden = !active;
        downloadAllButton.style.display = active ? 'inline-flex' : 'none';
        downloadButton?.classList.toggle('opacity-50', !active);
        downloadButton?.classList.toggle('cursor-not-allowed', !active);
        document.querySelectorAll('[data-export-column]').forEach((element) => element.classList.toggle('hidden', !active));
        if (!active) document.querySelectorAll('.prestasi-select, #select-all-prestasi').forEach((item) => item.checked = false);
    }
</script>
@endsection