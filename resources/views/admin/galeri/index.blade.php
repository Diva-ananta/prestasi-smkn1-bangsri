@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
<div class="page-shell">
    <div class="page-header animate-fade-in">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-500 dark:text-emerald-400">Media sekolah</p>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white md:text-3xl">Galeri</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Pilih foto dari prestasi yang sudah diinput, lalu validasi tampilannya.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/30 dark:text-red-300">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="section-card mb-6 animate-fade-in">
        <form action="{{ route('admin.galeri.store') }}" method="POST" class="grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
            @csrf
            <div>
                <label for="judul" class="admin-form-label">Judul foto <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="judul" name="judul" type="text" value="{{ old('judul') }}" placeholder="Contoh: Juara LKS 2026" class="admin-form-input w-full">
            </div>
            <div>
                <label for="prestasi_id" class="admin-form-label">Foto dari prestasi</label>
                <select id="prestasi_id" name="prestasi_id" required class="admin-form-input w-full">
                    <option value="">Pilih prestasi berfoto</option>
                    @foreach($prestasis as $prestasi)
                        <option value="{{ $prestasi->id }}" @selected(old('prestasi_id') == $prestasi->id)>{{ $prestasi->nama_lomba }} - {{ $prestasi->hasil }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="admin-btn-primary gap-2"><i class="fas fa-upload"></i>Tambah foto</button>
        </form>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($galeri as $item)
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="aspect-[4/3] overflow-hidden bg-slate-100 dark:bg-slate-800">
                    <img src="{{ asset('storage/' . ($item->prestasi?->foto ?: $item->foto)) }}" alt="{{ $item->judul ?: $item->prestasi?->nama_lomba ?: 'Foto galeri' }}" class="h-full w-full object-cover">
                </div>
                <div class="flex items-center justify-between gap-3 p-3">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $item->judul ?: $item->prestasi?->nama_lomba ?: 'Tanpa judul' }}</p>
                        <p class="mt-1 text-xs {{ $item->is_published ? 'text-emerald-600' : 'text-slate-400' }}">{{ $item->is_published ? 'Tampil publik' : 'Disembunyikan' }}</p>
                    </div>
                    <div class="flex shrink-0 gap-2">
                        <form action="{{ route('admin.galeri.visibility', $item) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg {{ $item->is_published ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}" title="{{ $item->is_published ? 'Sembunyikan foto' : 'Tampilkan foto' }}"><i class="fas {{ $item->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i></button>
                        </form>
                        <form action="{{ route('admin.galeri.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-300" title="Hapus foto"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center dark:border-slate-700">
                <i class="fas fa-images text-4xl text-slate-400"></i>
                <p class="mt-4 font-semibold text-slate-600 dark:text-slate-400">Belum ada foto galeri.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $galeri->links() }}</div>
</div>
@endsection
