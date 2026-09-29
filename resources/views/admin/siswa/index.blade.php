@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')
<div class="page-shell">
    <div class="page-header animate-fade-in">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-blue-500 dark:text-blue-400">Master Data</p>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white md:text-3xl">Data Siswa & Alumni</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Kelola data siswa aktif dan alumni SMK N 1 Bangsri.</p>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                <div class="flex w-full flex-wrap gap-2 sm:w-auto" data-export-controls="siswa">
                    <button type="button" onclick="toggleSiswaExportMode()" class="admin-btn-secondary min-h-11 min-w-0 flex-1 basis-[calc(50%-0.25rem)] justify-center px-2 text-[11px] leading-tight sm:flex-none sm:basis-auto sm:whitespace-nowrap sm:px-3 sm:text-sm" data-export-start><i class="fas fa-file-export mr-1 sm:mr-2"></i><span data-export-start-label>Export Excel</span></button>
                    <button type="button" onclick="exportSelectedSiswa('{{ route('admin.siswa.export') }}')" class="admin-btn-primary min-h-11 min-w-0 flex-1 basis-[calc(50%-0.25rem)] justify-center px-2 text-[11px] leading-tight sm:flex-none sm:basis-auto sm:whitespace-nowrap sm:px-3 sm:text-sm" data-export-download hidden style="display: none;" disabled><i class="fas fa-download mr-1 sm:mr-2"></i>Download pilihan</button>
                </div>
                <a href="{{ route('admin.sipintu.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-800 transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
                    <i class="fas fa-arrows-rotate"></i>
                    <span>SiPintu Gateway</span>
                </a>
                <a href="{{ route('admin.siswa.create') }}" class="admin-btn-primary gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Siswa</span>
                </a>
            </div>
        </div>
    </div>

    <div class="section-card animate-fade-in relative z-30 p-3 sm:p-4">
        <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end" data-live-search data-ajax-target="#siswa-results">
            <label class="relative block min-w-0 flex-1">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300">Cari siswa</span>
                <span class="pointer-events-none absolute bottom-0 left-3 flex h-12 items-center text-slate-400" aria-hidden="true"><i class="fas fa-search"></i></span>
                <input name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama, NIS, kelas, program keahlian..." aria-label="Cari siswa" class="admin-form-input mt-0 min-w-0 pl-10">
            </label>
            <details class="relative shrink-0">
                <summary class="flex min-h-11 cursor-pointer list-none items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 [&::-webkit-details-marker]:hidden dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">
                    <i class="fas fa-filter text-xs"></i>Filter
                    @if(request()->hasAny(['status', 'kelas', 'jurusan', 'angkatan']))<span class="h-2 w-2 rounded-full bg-blue-500" aria-label="Filter aktif"></span>@endif
                </summary>
                <div class="absolute right-0 top-full z-[70] mt-2 w-[min(90vw,42rem)] rounded-2xl border border-slate-200 bg-white p-4 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <label class="block min-w-0 text-xs font-semibold text-slate-600 dark:text-slate-300">Status
                            <select name="status" class="admin-form-input mt-1 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter status siswa">
                                <option value="">Semua status</option>
                                <option value="Aktif" @selected(($filters['status'] ?? '') === 'Aktif')>Aktif</option>
                                <option value="Alumni" @selected(($filters['status'] ?? '') === 'Alumni')>Alumni</option>
                            </select>
                        </label>
                        <label class="block min-w-0 text-xs font-semibold text-slate-600 dark:text-slate-300">Kelas
                            <select name="kelas" class="admin-form-input mt-1 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter kelas">
                                <option value="">Semua kelas</option>
                                @foreach($kelasOptions as $kelasOption)<option value="{{ $kelasOption }}" @selected(($filters['kelas'] ?? '') == $kelasOption)>{{ $kelasOption }}</option>@endforeach
                            </select>
                        </label>
                        <label class="block min-w-0 text-xs font-semibold text-slate-600 dark:text-slate-300">Program keahlian
                            <select name="jurusan" class="admin-form-input mt-1 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter program keahlian">
                                <option value="">Semua program</option>
                                @foreach($jurusanOptions as $jurusanOption)<option value="{{ $jurusanOption }}" @selected(($filters['jurusan'] ?? '') === $jurusanOption)>{{ $jurusanOption }}</option>@endforeach
                            </select>
                        </label>
                        <label class="block min-w-0 text-xs font-semibold text-slate-600 dark:text-slate-300">Angkatan
                            <select name="angkatan" class="admin-form-input mt-1 min-w-0 px-3 text-xs sm:text-sm" aria-label="Filter angkatan">
                                <option value="">Semua angkatan</option>
                                @foreach($angkatanOptions as $angkatanOption)<option value="{{ $angkatanOption }}" @selected(($filters['angkatan'] ?? '') == $angkatanOption)>{{ $angkatanOption }}</option>@endforeach
                            </select>
                        </label>
                    </div>
                    <div class="mt-4 flex justify-end gap-2 border-t border-slate-200 pt-3 dark:border-slate-700">
                        @if(request()->hasAny(['search', 'status', 'kelas', 'jurusan', 'angkatan']))
                            <a data-ajax-page href="{{ route('admin.siswa.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">Reset</a>
                        @endif
                        <button type="submit" class="admin-btn-primary"><i class="fas fa-check mr-2"></i>Terapkan</button>
                    </div>
                </div>
            </details>
        </form>
    </div>

    <div id="siswa-results">
    <!-- Status Tabs (Semua, Siswa Aktif, Alumni) -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
        <a href="{{ route('admin.siswa.index', array_merge(request()->except('status', 'page'))) }}"
           class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold transition {{ empty($status) ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-users text-xs"></i>
            <span>Semua Data</span>
            <span class="rounded-full px-2 py-0.5 text-[10px] {{ empty($status) ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' }}">{{ $totalAll }}</span>
        </a>

        <a href="{{ route('admin.siswa.index', array_merge(request()->except('page'), ['status' => 'Aktif'])) }}"
           class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold transition {{ $status === 'Aktif' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-user-check text-xs"></i>
            <span>Siswa Aktif</span>
            <span class="rounded-full px-2 py-0.5 text-[10px] {{ $status === 'Aktif' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' }}">{{ $totalAktif }}</span>
        </a>

        <a href="{{ route('admin.siswa.index', array_merge(request()->except('page'), ['status' => 'Alumni'])) }}"
           class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold transition {{ $status === 'Alumni' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-graduation-cap text-xs"></i>
            <span>Alumni</span>
            <span class="rounded-full px-2 py-0.5 text-[10px] {{ $status === 'Alumni' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300' }}">{{ $totalAlumni }}</span>
        </a>
    </div>

    <!-- Data Table -->
    <div class="section-card animate-fade-in">
        <x-admin.table class="admin-table admin-table-mobile-cards">
            <thead>
                <tr>
                    <th data-siswa-export-column class="hidden"><input type="checkbox" id="select-all-siswa" onclick="document.querySelectorAll('.siswa-select').forEach((item) => item.checked = this.checked)" aria-label="Pilih semua"></th>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Kelas / Tingkat</th>
                    <th>Program Keahlian</th>
                    <th>Angkatan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr>
                        <td data-siswa-export-column class="hidden"><input type="checkbox" class="siswa-select" value="{{ $siswa->id }}" aria-label="Pilih {{ $siswa->nama }}"></td>
                        <td>{{ ($siswas->currentPage() - 1) * $siswas->perPage() + $loop->iteration }}</td>
                        <td class="font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->nis }}</td>
                        <td class="font-medium text-slate-900 dark:text-white">{{ $siswa->nama }}</td>
                        <td>
                            @if($siswa->status === 'Alumni')
                                <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-2.5 py-0.5 text-[11px] font-bold text-purple-800 dark:bg-purple-900/40 dark:text-purple-300">
                                    <i class="fas fa-graduation-cap text-[10px]"></i>
                                    <span>Alumni</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-bold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Aktif</span>
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->status === 'Alumni')
                                <span class="text-slate-500 italic">Alumni {{ $siswa->tahun_lulus ? "({$siswa->tahun_lulus})" : '' }}</span>
                            @else
                                {{ $siswa->kelas }}
                            @endif
                        </td>
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
                        <td colspan="9" class="py-12 text-center text-slate-400">Belum ada data siswa ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>

    <div class="animate-fade-in">
        {{ $siswas->links() }}
    </div>
    </div>
</div>
<script>
    function toggleSiswaExportMode() {
        const active = !document.querySelector('.admin-table-mobile-cards')?.classList.contains('export-mode');
        const startButton = document.querySelector('[data-export-controls="siswa"] [data-export-start]');
        const startLabel = document.querySelector('[data-export-controls="siswa"] [data-export-start-label]');
        const downloadButton = document.querySelector('[data-export-controls="siswa"] [data-export-download]');
        document.querySelector('.admin-table-mobile-cards')?.classList.toggle('export-mode', active);
        startButton?.classList.toggle('bg-amber-100', active);
        startButton?.classList.toggle('text-amber-800', active);
        startLabel.textContent = active ? 'Batal pilih' : 'Export Excel';
        startButton?.querySelector('i')?.classList.toggle('fa-file-export', !active);
        startButton?.querySelector('i')?.classList.toggle('fa-xmark', active);
        downloadButton.disabled = !active;
        downloadButton.hidden = !active;
        downloadButton.style.display = active ? 'inline-flex' : 'none';
        downloadButton?.classList.toggle('opacity-50', !active);
        downloadButton?.classList.toggle('cursor-not-allowed', !active);
        document.querySelectorAll('[data-siswa-export-column]').forEach((element) => element.classList.toggle('hidden', !active));
        if (!active) document.querySelectorAll('.siswa-select, #select-all-siswa').forEach((item) => item.checked = false);
    }

    function exportSelectedSiswa(url) {
        const ids = [...document.querySelectorAll('.siswa-select:checked')].map((item) => `ids[]=${encodeURIComponent(item.value)}`);
        if (!ids.length) return window.adminNotify?.('Pilih minimal satu data untuk diekspor.', 'error');
        window.location.href = `${url}?${ids.join('&')}`;
    }
</script>
@endsection
