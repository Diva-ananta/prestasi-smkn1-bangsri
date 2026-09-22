@php
    $isEdit = isset($siswa);
    $currentFoto = $isEdit && $siswa->foto ? asset('storage/' . $siswa->foto) : null;

    $jenisKelamin = $jenisKelamin ?? ['L' => 'Laki-laki', 'P' => 'Perempuan'];
    $kelasOptions = $kelasOptions ?? ['10' => 'X', '11' => 'XI', '12' => 'XII'];
    $jurusanOptions = $jurusanOptions ?? [
        'PPLG' => 'Pengembangan Perangkat Lunak dan Gim',
        'TO' => 'Teknik Otomotif',
        'MPLB' => 'Manajemen Perkantoran dan Layanan Bisnis',
        'AKL' => 'Akuntansi & Keuangan Lembaga',
        'PM' => 'Pemasaran',
    ];
@endphp

@if ($errors->any())
    <div class="mb-6 flex items-start gap-3 rounded-[22px] border-l-4 border-red-500 bg-red-50/90 p-4 text-sm text-red-700 dark:bg-red-950/30 dark:text-red-300">
        <i class="fas fa-exclamation-circle mt-0.5"></i>
        <div>
            <p class="font-semibold">Periksa kembali data yang diisi.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="space-y-6">
    <section class="rounded-[24px] border border-slate-200 bg-white/60 p-5 dark:border-slate-700 dark:bg-slate-900/40 md:p-6">
        <div class="mb-5 flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                <i class="fas fa-user-graduate text-sm"></i>
            </span>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Data Identitas</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Identitas utama siswa.</p>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="nis" class="text-sm font-semibold text-slate-700 dark:text-slate-200">NIS <span class="text-red-500">*</span></label>
                <input id="nis" type="text" name="nis" value="{{ old('nis', $siswa->nis ?? '') }}" class="admin-form-input" placeholder="Masukkan NIS" required autofocus maxlength="20">
                @error('nis') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nisn" class="text-sm font-semibold text-slate-700 dark:text-slate-200">NISN <span class="text-slate-400 font-normal">(opsional)</span></label>
                <input id="nisn" type="text" name="nisn" value="{{ old('nisn', $siswa->nisn ?? '') }}" class="admin-form-input" placeholder="Masukkan NISN (opsional)" maxlength="20">
                @error('nisn') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="nama" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Nama Lengkap <span class="text-red-500">*</span></label>
                <input id="nama" type="text" name="nama" value="{{ old('nama', $siswa->nama ?? '') }}" class="admin-form-input" placeholder="Masukkan nama lengkap siswa" required maxlength="100">
                @error('nama') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="foto" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Foto Siswa</label>
                <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-start">
                    <div class="flex aspect-[4/4] w-32 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50 dark:border-slate-600 dark:bg-slate-800">
                        <img id="fotoPreview" src="{{ $currentFoto }}" alt="Preview foto siswa" class="h-full w-full object-cover {{ $currentFoto ? '' : 'hidden' }}">
                        <i id="fotoPreviewIcon" class="fas fa-user text-2xl text-slate-300 dark:text-slate-600 {{ $currentFoto ? 'hidden' : '' }}"></i>
                    </div>
                    <div class="flex-1">
                        <input id="foto" type="file" name="foto" accept=".jpg,.jpeg,.png" class="admin-form-input">
                        <p class="mt-2 text-xs leading-5 text-slate-400">JPG/PNG, maksimal 2MB. Foto dapat dipotong dan diatur dengan rasio <strong>4:5</strong> sebelum disimpan.</p>
                        <p class="mt-3 flex items-center gap-2 text-xs text-emerald-600 dark:text-emerald-400"><i class="fas fa-crop-alt"></i>Atur posisi foto pada area crop agar tampilannya konsisten.</p>
                        @if ($currentFoto)
                            <button type="button" id="recropExistingFoto" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 transition hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300"><i class="fas fa-crop-alt"></i> Atur ulang foto yang sudah ada</button>
                        @endif
                    </div>
                </div>
                @error('foto') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="jenis_kelamin" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="admin-form-input" required>
                    <option value="">Pilih jenis kelamin</option>
                    @foreach ($jenisKelamin as $value => $label)
                        <option value="{{ $value }}" @selected(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jenis_kelamin') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <section class="rounded-[24px] border border-slate-200 bg-white/60 p-5 dark:border-slate-700 dark:bg-slate-900/40 md:p-6">
        <div class="mb-5 flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                <i class="fas fa-school text-sm"></i>
            </span>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Data Akademik</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Kelas, program keahlian, dan tahun angkatan.</p>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="kelas" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Kelas <span class="text-red-500">*</span></label>
                <select id="kelas" name="kelas" class="admin-form-input" required>
                    <option value="">Pilih kelas</option>
                    @foreach ($kelasOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('kelas', $siswa->kelas ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('kelas') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="jurusan" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Program Keahlian <span class="text-red-500">*</span></label>
                <select id="jurusan" name="jurusan" class="admin-form-input" required>
                    <option value="">Pilih Program Keahlian</option>
                    @foreach ($jurusanOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('jurusan', $siswa->jurusan ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jurusan') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="angkatan" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Angkatan <span class="text-red-500">*</span></label>
                <input id="angkatan" type="number" name="angkatan" value="{{ old('angkatan', $siswa->angkatan ?? date('Y')) }}" min="2000" max="{{ date('Y') + 1 }}" class="admin-form-input" required>
                @error('angkatan') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $siswa->is_published ?? true)) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span><span class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Tampilkan di portal publik</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Siswa hanya muncul di halaman publik jika opsi ini aktif dan memiliki prestasi yang berstatus Publish.</span></span>
                </label>
                @error('is_published') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
        <a href="{{ route('admin.siswa.index') }}" class="admin-btn-secondary">Batal</a>

        <button type="submit" class="admin-btn-primary shadow-lg shadow-emerald-700/20">
            <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Siswa' }}
        </button>
    </div>
</div>

<div id="cropModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm">
    <div class="flex max-h-[95vh] w-full max-w-3xl flex-col overflow-hidden rounded-[24px] bg-white shadow-2xl dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-700">
            <div><h3 class="text-sm font-bold text-slate-900 dark:text-white">Potong Foto Siswa</h3><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Atur posisi foto. Rasio dikunci 4:5.</p></div>
            <button type="button" id="cropCancelTop" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"><i class="fas fa-times"></i></button>
        </div>
        <div class="min-h-0 flex-1 overflow-auto bg-slate-950 p-4"><div class="mx-auto max-h-[65vh] max-w-2xl"><img id="cropImage" src="" alt="Foto yang akan dipotong" class="block max-h-[65vh] max-w-full"></div></div>
        <div class="border-t border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
            <div class="mb-4 flex items-center gap-3">
                <button type="button" id="cropZoomOut" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 dark:border-slate-700 dark:text-slate-300"><i class="fas fa-minus"></i></button>
                <input id="cropZoom" type="range" min="0.1" max="3" step="0.05" value="1" class="h-2 flex-1 cursor-pointer appearance-none rounded-lg bg-slate-200 accent-emerald-600 dark:bg-slate-700">
                <button type="button" id="cropZoomIn" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 dark:border-slate-700 dark:text-slate-300"><i class="fas fa-plus"></i></button>
            </div>
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" id="cropCancel" class="admin-btn-secondary">Batal</button>
                <button type="button" id="cropReset" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 dark:border-slate-700 dark:text-slate-300"><i class="fas fa-undo"></i> Reset</button>
                <button type="button" id="cropApply" class="admin-btn-primary"><i class="fas fa-check mr-2"></i>Gunakan Foto</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
(() => {
    const input = document.getElementById('foto'), modal = document.getElementById('cropModal'), image = document.getElementById('cropImage');
    const preview = document.getElementById('fotoPreview'), previewIcon = document.getElementById('fotoPreviewIcon'), zoom = document.getElementById('cropZoom');
    const recropExistingFoto = document.getElementById('recropExistingFoto');
    let cropper, objectUrl, cropRequest = 0, cropperLoader;
    const ensureCropper = () => {
        if (window.Cropper) return Promise.resolve();
        if (cropperLoader) return cropperLoader;
        cropperLoader = new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js';
            script.onload = resolve;
            script.onerror = () => reject(new Error('CropperJS gagal dimuat.'));
            document.head.appendChild(script);
        });
        return cropperLoader;
    };
    const close = () => {
        cropRequest++;
        cropper?.destroy(); cropper = null;
        modal.classList.add('hidden'); modal.classList.remove('flex');
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        objectUrl = null; image.src = '';
    };
    const openCrop = async (source, isObjectUrl = false) => {
        const requestId = ++cropRequest;
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        objectUrl = isObjectUrl ? source : null;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        image.src = source;
        try {
            await Promise.all([ensureCropper(), image.decode()]);
            if (requestId !== cropRequest) return;
            cropper?.destroy();
            cropper = new Cropper(image, { aspectRatio: 4 / 5, viewMode: 1, dragMode: 'move', autoCropArea: .9, responsive: true, restore: false, background: false, movable: true, zoomable: true, rotatable: false, scalable: false, cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false, ready: () => zoom.value = 1 });
        } catch (error) {
            if (requestId !== cropRequest) return;
            close();
            alert('Crop foto tidak dapat dibuka. Periksa koneksi internet lalu coba lagi.');
        }
    };
    input?.addEventListener('change', () => {
        const file = input.files?.[0];
        if (!file) return;
        if (!['image/jpeg', 'image/png'].includes(file.type) || file.size > 2 * 1024 * 1024) {
            alert(!['image/jpeg', 'image/png'].includes(file.type) ? 'Foto harus berformat JPG atau PNG.' : 'Ukuran foto maksimal 2MB.');
            input.value = ''; return;
        }
        openCrop(URL.createObjectURL(file), true);
    });
    recropExistingFoto?.addEventListener('click', () => openCrop(preview.src));
    zoom?.addEventListener('input', () => cropper?.zoomTo(parseFloat(zoom.value)));
    document.getElementById('cropZoomIn')?.addEventListener('click', () => { zoom.value = Math.min(3, parseFloat(zoom.value) + .1); cropper?.zoomTo(parseFloat(zoom.value)); });
    document.getElementById('cropZoomOut')?.addEventListener('click', () => { zoom.value = Math.max(.1, parseFloat(zoom.value) - .1); cropper?.zoomTo(parseFloat(zoom.value)); });
    document.getElementById('cropReset')?.addEventListener('click', () => { cropper?.reset(); zoom.value = 1; });
    document.getElementById('cropCancel')?.addEventListener('click', close);
    document.getElementById('cropCancelTop')?.addEventListener('click', close);
    document.getElementById('cropApply')?.addEventListener('click', () => {
        const canvas = cropper?.getCroppedCanvas({ width: 600, height: 750, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
        if (!canvas) return;
        canvas.toBlob(blob => {
            if (!blob) return;
            const file = new File([blob], 'foto-siswa-4x5.jpg', { type: 'image/jpeg', lastModified: Date.now() });
            const transfer = new DataTransfer(); transfer.items.add(file); input.files = transfer.files;
            preview.src = URL.createObjectURL(file); preview.classList.remove('hidden'); previewIcon.classList.add('hidden'); close();
        }, 'image/jpeg', .92);
    });
})();
</script>
