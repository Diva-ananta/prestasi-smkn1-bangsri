@php
    $isEdit = isset($artikel);
    $selectedPrestasiId = old('prestasi_id', $artikel->prestasi_id ?? '');
    $prestasiOptions = $prestasis->map(function ($prestasi) {
        return [
            'id' => (string) $prestasi->id,
            'judul' => $prestasi->nama_lomba . ' - ' . $prestasi->hasil,
            'isi' => 'Prestasi ' . $prestasi->nama_lomba . ' berhasil diraih dengan hasil ' . $prestasi->hasil . ' pada tingkat ' . ($prestasi->tingkat ?? 'umum') . '. ' . ($prestasi->keterangan ?? ''),
        ];
    })->values();
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.artikel.update', $artikel) : route('admin.artikel.store') }}" enctype="multipart/form-data" data-ajax-form x-data="articleForm()">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="space-y-5">
        <div>
            <label for="source" class="mb-2 block text-sm font-semibold text-slate-700">Sumber Artikel</label>
            <select id="source" x-model="source" @change="applyPrestasi()" class="admin-form-input">
                <option value="manual">Tulis manual dari awal</option>
                <option value="prestasi">Berdasarkan prestasi yang sudah ada</option>
            </select>
            <p class="mt-1.5 text-xs text-slate-500">Pilih prestasi untuk mengisi draf awal yang masih dapat diubah.</p>
        </div>

        <div x-show="source === 'prestasi'" x-cloak>
            <label for="prestasi_id" class="mb-2 block text-sm font-semibold text-slate-700">Prestasi Terkait</label>
            <select id="prestasi_id" name="prestasi_id" x-model="prestasiId" x-bind:disabled="source !== 'prestasi'" @change="applyPrestasi()" class="admin-form-input">
                <option value="">Pilih prestasi</option>
                @foreach($prestasis as $prestasi)
                    <option value="{{ $prestasi->id }}">{{ $prestasi->nama_lomba }} - {{ $prestasi->hasil }} ({{ $prestasi->tanggal_mulai?->format('Y') ?? '-' }})</option>
                @endforeach
            </select>
            @error('prestasi_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <input type="hidden" name="prestasi_id" x-bind:disabled="source !== 'manual'" value="">

        <div>
            <label for="judul" class="mb-2 block text-sm font-semibold text-slate-700">Judul</label>
            <input id="judul" type="text" name="judul" x-model="judul" value="{{ old('judul', $artikel->judul ?? '') }}" class="admin-form-input" required>
            @error('judul') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="isi" class="mb-2 block text-sm font-semibold text-slate-700">Isi Artikel</label>
            <textarea id="isi" name="isi" rows="10" x-model="isi" class="admin-form-input" required>{{ old('isi', $artikel->isi ?? '') }}</textarea>
            @error('isi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="gambar" class="mb-2 block text-sm font-semibold text-slate-700">Gambar Artikel</label>
            <input id="gambar" type="file" name="gambar" accept=".jpg,.jpeg,.png" class="admin-form-input file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-100 file:px-3 file:py-2 file:font-semibold file:text-emerald-800">
            @if($isEdit && $artikel->gambar)<p class="mt-1.5 text-xs text-slate-500">Gambar saat ini akan dipertahankan jika tidak memilih gambar baru.</p>@else<p class="mt-1.5 text-xs text-slate-500">Jika memakai sumber prestasi dan tidak memilih file, foto prestasi akan dipakai.</p>@endif
            @error('gambar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="penulis" class="mb-2 block text-sm font-semibold text-slate-700">Penulis</label>
                <input id="penulis" type="text" name="penulis" value="{{ old('penulis', $artikel->penulis ?? Auth::user()->name) }}" class="admin-form-input">
                @error('penulis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tanggal_publikasi" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Publikasi</label>
                <input id="tanggal_publikasi" type="date" name="tanggal_publikasi" value="{{ old('tanggal_publikasi', isset($artikel) && $artikel->tanggal_publikasi ? $artikel->tanggal_publikasi->format('Y-m-d') : date('Y-m-d')) }}" class="admin-form-input">
                @error('tanggal_publikasi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
            <select id="status" name="status" class="admin-form-input">
                <option value="Draft" {{ old('status', $artikel->status ?? 'Draft') === 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Publish" {{ old('status', $artikel->status ?? 'Draft') === 'Publish' ? 'selected' : '' }}>Publish</option>
            </select>
            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
        <a href="{{ route('admin.artikel.index') }}" class="admin-btn-secondary">Batal</a>
        <button type="submit" class="admin-btn-primary"><i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Artikel' }}</button>
    </div>
</form>

<script>
    function articleForm() {
        const prestasis = @json($prestasiOptions);
        return {
            source: '{{ $selectedPrestasiId ? 'prestasi' : 'manual' }}',
            prestasiId: '{{ $selectedPrestasiId }}',
            judul: @js(old('judul', $artikel->judul ?? '')),
            isi: @js(old('isi', $artikel->isi ?? '')),
            applyPrestasi() {
                if (this.source !== 'prestasi') {
                    this.prestasiId = '';
                    return;
                }
                const prestasi = prestasis.find((item) => item.id === this.prestasiId);
                if (prestasi && !this.judul && !this.isi) {
                    this.judul = prestasi.judul;
                    this.isi = prestasi.isi;
                }
            },
        };
    }
</script>
