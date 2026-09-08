@extends('layouts.admin')

@section('title', 'Data Artikel')

@section('content')
<div>
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-emerald-700">Manajemen Konten</p>
            <h1 class="text-2xl font-bold text-slate-800">{{ isset($artikel) ? 'Edit Artikel' : 'Data Artikel' }}</h1>
            <p class="text-sm text-slate-500">Kelola artikel manual atau mulai dari prestasi yang sudah tercatat.</p>
        </div>
        <button type="button" onclick="openArticleModal()" class="admin-btn-primary"><i class="fas fa-plus mr-2"></i>Buat Artikel</button>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Artikel belum tersimpan.</p>
            <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="mb-4 flex items-center justify-between"><div><h2 class="text-lg font-bold text-slate-800">Daftar Artikel</h2><p class="text-sm text-slate-500">Artikel yang tersimpan di sistem.</p></div></div>
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">No</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Judul</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Sumber Prestasi</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Penulis</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artikels as $artikelRow)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-6 py-3">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 font-medium">{{ $artikelRow->judul }}</td>
                        <td class="px-6 py-3">{{ $artikelRow->prestasi?->nama_lomba ?? 'Manual' }}</td>
                        <td class="px-6 py-3">{{ $artikelRow->penulis ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $artikelRow->status == 'Publish' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $artikelRow->status }}
                            </span>
                        </td>
                        <td class="px-6 py-3">{{ $artikelRow->tanggal_publikasi ? $artikelRow->tanggal_publikasi->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a data-ajax-page href="{{ route('admin.artikel.show', $artikelRow) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                                <a data-ajax-page href="{{ route('admin.artikel.edit', $artikelRow) }}" title="Edit artikel" class="text-yellow-600 hover:text-yellow-800"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.artikel.destroy', $artikelRow) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">Belum ada artikel.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $artikels->links() }}</div>

    <div id="article-modal" class="{{ isset($artikel) || $errors->any() ? 'flex' : 'hidden' }} fixed inset-0 z-[60] items-start justify-center overflow-y-auto bg-slate-950/60 px-4 py-6 backdrop-blur-sm sm:py-10" role="dialog" aria-modal="true" aria-labelledby="artikel-form-title">
        <div id="article-modal-panel" class="admin-form-bubble relative my-auto w-full max-w-3xl max-h-[calc(100vh-3rem)] overflow-y-auto p-6 md:p-8">
            <button type="button" onclick="closeArticleModal()" class="absolute right-5 top-5 flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup form"><i class="fas fa-times"></i></button>
            <div class="mb-6 flex items-start gap-3 pr-10">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"><i class="fas {{ isset($artikel) ? 'fa-pen' : 'fa-plus' }}"></i></span>
                <div><h2 id="artikel-form-title" class="text-lg font-bold text-slate-800">{{ isset($artikel) ? 'Perbarui Artikel' : 'Buat Artikel Baru' }}</h2><p class="text-sm text-slate-500">Pilih sumber artikel, lalu ubah isinya sesuai kebutuhan.</p></div>
            </div>
            @include('admin.artikel._form')
        </div>
    </div>
</div>

<script>
    function openArticleModal() {
        const modal = document.getElementById('article-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        document.getElementById('source')?.focus();
    }

    function closeArticleModal() {
        const modal = document.getElementById('article-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('article-modal')?.addEventListener('click', function (event) {
        if (event.target === this) closeArticleModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeArticleModal();
    });

    @if(isset($artikel) || $errors->any())
        document.body.classList.add('overflow-hidden');
    @endif
</script>
@endsection