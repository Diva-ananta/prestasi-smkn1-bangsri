@php
    $isEdit = isset($siswa);

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
                <label for="nisn" class="text-sm font-semibold text-slate-700 dark:text-slate-200">NISN <span class="text-red-500">*</span></label>
                <input id="nisn" type="text" name="nisn" value="{{ old('nisn', $siswa->nisn ?? '') }}" class="admin-form-input" placeholder="Masukkan NISN" required maxlength="20">
                @error('nisn') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="nama" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Nama Lengkap <span class="text-red-500">*</span></label>
                <input id="nama" type="text" name="nama" value="{{ old('nama', $siswa->nama ?? '') }}" class="admin-form-input" placeholder="Masukkan nama lengkap siswa" required maxlength="100">
                @error('nama') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="foto" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Foto Siswa</label>
                <input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="admin-form-input">
                @if (!empty($siswa?->foto)) <img src="{{ asset('storage/'.$siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="mt-3 h-20 w-20 rounded-xl object-cover"> @endif
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
                <label for="jurusan" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Jurusan <span class="text-red-500">*</span></label>
                <select id="jurusan" name="jurusan" class="admin-form-input" required>
                    <option value="">Pilih jurusan</option>
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