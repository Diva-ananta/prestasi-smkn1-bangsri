@php
    $isEdit = isset($prestasi);
    $inputClass = 'admin-form-input';
    $dateValue = fn ($date) => $date?->format('Y-m-d');
    $currentFoto = $isEdit && $prestasi->foto ? asset('storage/' . $prestasi->foto) : null;
    $ekstrakurikulerOptions = config('app.ekstrakurikuler', []);
    $currentEkstrakurikuler = old('nama_tim', $prestasi->nama_tim ?? '');
    if ($currentEkstrakurikuler && !array_key_exists($currentEkstrakurikuler, $ekstrakurikulerOptions)) {
        $ekstrakurikulerOptions = [$currentEkstrakurikuler => null] + $ekstrakurikulerOptions;
    }
@endphp

@if($errors->any())
    <div class="mb-6 flex items-start gap-3 rounded-[22px] border-l-4 border-red-500 bg-red-50/90 p-4 text-sm text-red-700 shadow-lg shadow-red-900/5 dark:bg-red-950/30 dark:text-red-300">
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-300"><i class="fas fa-exclamation-circle"></i></span>
        <div>
            <p class="font-semibold">Periksa kembali data yang diisi.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-[1fr_300px] lg:items-start">
    {{-- ===================== KOLOM FORM ===================== --}}
    <div class="space-y-6">

        {{-- Section 1: Informasi Lomba --}}
        <div class="rounded-[24px] border border-slate-200 bg-white/60 p-5 dark:border-slate-700 dark:bg-slate-900/40 md:p-6">
            <div class="mb-5 flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300"><i class="fas fa-trophy text-sm"></i></span>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Informasi Lomba</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Detail utama tentang kompetisi dan hasilnya.</p>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="nama_lomba" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Judul Prestasi <span class="text-red-500">*</span></label>
                    <input id="nama_lomba" type="text" name="nama_lomba" value="{{ old('nama_lomba', $prestasi->nama_lomba ?? '') }}" class="{{ $inputClass }} w-full" placeholder="Contoh: Juara 1 Lomba Safety Riding" required autofocus maxlength="150">
                    @error('nama_lomba') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="penyelenggara" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Penyelenggara</label>
                    <input id="penyelenggara" type="text" name="penyelenggara" value="{{ old('penyelenggara', $prestasi->penyelenggara ?? '') }}" class="{{ $inputClass }} w-full" placeholder="Contoh: Dinas Pendidikan Kabupaten Jepara" maxlength="255">
                    @error('penyelenggara') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="hasil" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Hasil / Peringkat <span class="text-red-500">*</span></label>
                    <input id="hasil" type="text" name="hasil" value="{{ old('hasil', $prestasi->hasil ?? '') }}" class="{{ $inputClass }}" placeholder="Contoh: Juara Harapan 1" required>
                    @error('hasil') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tingkat" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Tingkat</label>
                    <select id="tingkat" name="tingkat" class="{{ $inputClass }}">
                        <option value="">Pilih tingkat</option>
                        @foreach(['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'] as $option)
                            <option value="{{ $option }}" @selected(old('tingkat', $prestasi->tingkat ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('tingkat') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kategori" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Kategori Lomba</label>
                    <select id="kategori" name="kategori" class="{{ $inputClass }}">
                        <option value="">Pilih kategori lomba</option>
                        @foreach(['Akademik', 'Seni', 'Olahraga', 'Keagamaan', 'Teknologi', 'Keterampilan', 'Lainnya'] as $option)
                            <option value="{{ $option }}" @selected(old('kategori', $prestasi->kategori ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="lokasi" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Lokasi Lomba</label>
                    <input id="lokasi" type="text" name="lokasi" value="{{ old('lokasi', $prestasi->lokasi ?? '') }}" class="{{ $inputClass }}" placeholder="Contoh: GOR Jepara" maxlength="255">
                    @error('lokasi') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_mulai" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Tanggal Perolehan</label>
                    <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $dateValue($prestasi->tanggal_mulai ?? null)) }}" class="{{ $inputClass }}">
                    @error('tanggal_mulai') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section 2: Peserta --}}
        <div class="rounded-[24px] border border-slate-200 bg-white/60 p-5 dark:border-slate-700 dark:bg-slate-900/40 md:p-6">
            <div class="mb-5 flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300"><i class="fas fa-users text-sm"></i></span>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Peserta</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Siapa saja siswa yang meraih prestasi ini.</p>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="jenis_peserta" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Jenis Peserta <span class="text-red-500">*</span></label>
                    <select id="jenis_peserta" name="jenis_peserta" class="{{ $inputClass }}" required>
                        <option value="Individu" @selected(old('jenis_peserta', $prestasi->jenis_peserta ?? 'Individu') === 'Individu')>Individu</option>
                        <option value="Tim" @selected(old('jenis_peserta', $prestasi->jenis_peserta ?? '') === 'Tim')>Tim</option>
                    </select>
                    @error('jenis_peserta') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="namaTimWrapper">
                    <label for="nama_tim" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Ekstrakurikuler <span class="text-red-500">*</span></label>
                    <select id="nama_tim" name="nama_tim" class="{{ $inputClass }}">
                        <option value="">Pilih ekstrakurikuler</option>
                        @foreach($ekstrakurikulerOptions as $option => $url)
                            <option value="{{ $option }}" @selected($currentEkstrakurikuler === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('nama_tim') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="siswa_id" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Nama Siswa <span class="text-red-500">*</span></label>

                    <div id="siswaSearchContainer" class="relative mt-2">
                        <div class="relative">
                            <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                            <input id="siswa_query" type="search" placeholder="Cari nama siswa atau NIS..." class="{{ $inputClass }} pl-10" autocomplete="off">
                        </div>
                        <ul id="siswaResults" class="absolute z-20 mt-2 max-h-48 w-full overflow-auto rounded-xl border border-slate-200 bg-white p-2 shadow-lg hidden dark:border-slate-700 dark:bg-slate-800"></ul>
                    </div>

                    <div id="siswaSelected" class="mt-3 flex flex-wrap gap-2">
                        @php
                            $preselected = old('siswa_id', $selectedSiswa ?? []);
                        @endphp
                        @foreach($preselected as $sid)
                            @php $s = $siswas->firstWhere('id', $sid); @endphp
                            @if($s)
                                <div class="selected-chip flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300" data-id="{{ $s->id }}">
                                    <i class="fas fa-user-graduate text-xs"></i>
                                    <span>{{ $s->nama }} ({{ $s->nis }})</span>
                                    <button type="button" class="remove-chip ml-1 text-emerald-700/60 hover:text-red-600 dark:text-emerald-400/60">&times;</button>
                                    <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <p id="siswaHelp" class="mt-2 text-xs text-slate-500 dark:text-slate-400">Peserta individu hanya dapat memilih satu siswa.</p>
                    @error('siswa_id') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('siswa_id.*') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section 3: Media & Publikasi --}}
        <div class="rounded-[24px] border border-slate-200 bg-white/60 p-5 dark:border-slate-700 dark:bg-slate-900/40 md:p-6">
            <div class="mb-5 flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300"><i class="fas fa-image text-sm"></i></span>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Media & Publikasi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Foto, deskripsi, dan status tampil di portal publik.</p>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="foto" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Thumbnail Prestasi</label>
                    <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-center">
                        <div id="fotoPreviewWrap" class="flex h-28 w-44 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50 dark:border-slate-600 dark:bg-slate-800">
                            <img id="fotoPreview" src="{{ $currentFoto }}" alt="Preview thumbnail" class="h-full w-full object-cover {{ $currentFoto ? '' : 'hidden' }}">
                            <i id="fotoPreviewIcon" class="fas fa-image text-2xl text-slate-300 dark:text-slate-600 {{ $currentFoto ? 'hidden' : '' }}"></i>
                        </div>
                        <div class="flex-1">
                            <input id="foto" type="file" name="foto" accept=".jpg,.jpeg,.png" class="{{ $inputClass }} file:mr-3 file:rounded-xl file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 dark:file:bg-emerald-900/40 dark:file:text-emerald-300">
                            <p class="mt-2 text-xs text-slate-400">Format JPG/PNG, disarankan rasio 4:3, maks 2MB.</p>
                        </div>
                    </div>
                    @error('foto') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Status Publikasi <span class="text-red-500">*</span></label>
                    <select id="status" name="status" class="{{ $inputClass }}" required>
                        <option value="Draft" @selected(old('status', $prestasi->status ?? 'Draft') === 'Draft')>Draft</option>
                        <option value="Publish" @selected(old('status', $prestasi->status ?? '') === 'Publish')>Publish</option>
                    </select>
                    <p class="mt-2 text-xs text-slate-400">Draft belum tampil di portal publik.</p>
                    @error('status') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center justify-between">
                        <label for="keterangan" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Keterangan Singkat</label>
                        <span id="keteranganCount" class="text-xs text-slate-400">0/500</span>
                    </div>
                    <textarea id="keterangan" name="keterangan" rows="3" maxlength="500" class="{{ $inputClass }}" placeholder="Tambahkan cerita singkat tentang prestasi ini (opsional).">{{ old('keterangan', $prestasi->keterangan ?? '') }}</textarea>
                    @error('keterangan') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
            <a href="{{ route('admin.prestasi.index') }}" class="admin-btn-secondary">Batal</a>
            <button type="submit" class="admin-btn-primary shadow-lg shadow-emerald-700/20">
                <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Prestasi' }}
            </button>
        </div>
    </div>

    {{-- ===================== SIDEBAR PREVIEW ===================== --}}
    <aside class="lg:sticky lg:top-24">
        <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Pratinjau kartu publik</p>

            <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="relative flex h-28 items-center justify-center overflow-hidden bg-gradient-to-br from-slate-100 to-emerald-50 dark:from-slate-800 dark:to-slate-900">
                    <img id="previewFoto" src="{{ $currentFoto }}" class="h-full w-full object-cover {{ $currentFoto ? '' : 'hidden' }}" alt="">
                    <i id="previewFotoIcon" class="fas fa-trophy text-3xl text-emerald-300 dark:text-emerald-700 {{ $currentFoto ? 'hidden' : '' }}"></i>
                </div>
                <div class="p-4">
                    <div class="mb-2 flex flex-wrap gap-1.5">
                        <span id="previewHasil" class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">{{ old('hasil', $prestasi->hasil ?? 'Hasil') }}</span>
                        <span id="previewTingkat" class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">{{ old('tingkat', $prestasi->tingkat ?? 'Tingkat') }}</span>
                    </div>
                    <p id="previewJudul" class="text-sm font-black leading-snug text-slate-900 dark:text-white">{{ old('nama_lomba', $prestasi->nama_lomba ?? 'Judul prestasi akan tampil di sini') }}</p>
                    <p id="previewStatus" class="mt-3 text-[11px] font-semibold {{ ($prestasi->status ?? 'Draft') === 'Publish' ? 'text-emerald-600' : 'text-slate-400' }}">
                        <i class="fas fa-circle mr-1 text-[6px]"></i>{{ old('status', $prestasi->status ?? 'Draft') }}
                    </p>
                </div>
            </div>

            <div class="mt-5 space-y-2 border-t border-slate-100 pt-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400">
                <p class="flex items-center gap-2"><i class="fas fa-check-circle w-4 text-emerald-500"></i>Isi judul & hasil sejelas mungkin.</p>
                <p class="flex items-center gap-2"><i class="fas fa-check-circle w-4 text-emerald-500"></i>Gunakan foto beresolusi baik.</p>
                <p class="flex items-center gap-2"><i class="fas fa-check-circle w-4 text-emerald-500"></i>Set status "Publish" agar tampil di portal.</p>
            </div>
        </div>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisPeserta = document.getElementById('jenis_peserta');
        const namaTimWrapper = document.getElementById('namaTimWrapper');
        const namaTim = document.getElementById('nama_tim');
        const siswaHelp = document.getElementById('siswaHelp');

        const siswaQuery = document.getElementById('siswa_query');
        const siswaResults = document.getElementById('siswaResults');
        const siswaSelected = document.getElementById('siswaSelected');

        function toggleNamaTim() {
            const isTeam = jenisPeserta.value === 'Tim';
            namaTimWrapper.hidden = !isTeam;
            namaTim.disabled = !isTeam;
            siswaHelp.textContent = isTeam
                ? 'Pilih satu atau lebih siswa untuk peserta tim.'
                : 'Peserta individu hanya dapat memilih satu siswa.';

            if (!isTeam) {
                const chips = siswaSelected.querySelectorAll('[data-id]');
                if (chips.length > 1) {
                    chips.forEach((chip, idx) => {
                        if (idx > 0) chip.remove();
                    });
                }
            }
        }

        let debounceTimer = null;
        siswaQuery.addEventListener('input', function () {
            const q = this.value.trim();
            clearTimeout(debounceTimer);
            if (!q) { siswaResults.classList.add('hidden'); siswaResults.innerHTML = ''; return; }
            debounceTimer = setTimeout(() => fetchSiswa(q), 300);
        });

        async function fetchSiswa(q) {
            try {
                const url = new URL("/admin/siswa/search", window.location.origin);
                url.searchParams.set('q', q);
                const res = await fetch(url.toString());
                if (!res.ok) throw new Error('Network error');
                const data = await res.json();
                renderResults(data);
            } catch (err) {
                console.error(err);
            }
        }

        function renderResults(items) {
            siswaResults.innerHTML = '';
            if (!items.length) {
                siswaResults.innerHTML = '<li class="px-3 py-2 text-sm text-slate-500">Tidak ditemukan.</li>';
            } else {
                items.forEach(i => {
                    const li = document.createElement('li');
                    li.className = 'cursor-pointer rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 dark:text-slate-200';
                    li.textContent = `${i.nama} (${i.nis}) — ${i.kelas ?? ''}`;
                    li.dataset.id = i.id;
                    li.dataset.nama = i.nama;
                    li.dataset.nis = i.nis;
                    li.addEventListener('click', () => selectSiswa(i));
                    siswaResults.appendChild(li);
                });
            }
            siswaResults.classList.remove('hidden');
        }

        function selectSiswa(item) {
            const isTeam = jenisPeserta.value === 'Tim';
            const existing = siswaSelected.querySelector(`[data-id='${item.id}']`);
            if (existing) return;

            if (!isTeam) {
                siswaSelected.querySelectorAll('[data-id]').forEach(e => e.remove());
            }

            const chip = document.createElement('div');
            chip.className = 'selected-chip flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300';
            chip.dataset.id = item.id;
            chip.innerHTML = `<i class="fas fa-user-graduate text-xs"></i><span>${escapeHtml(item.nama)} (${escapeHtml(item.nis)})</span>`;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'remove-chip ml-1 text-emerald-700/60 hover:text-red-600 dark:text-emerald-400/60';
            btn.innerHTML = '&times;';
            btn.addEventListener('click', () => chip.remove());

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'siswa_id[]';
            hidden.value = item.id;

            chip.appendChild(btn);
            chip.appendChild(hidden);
            siswaSelected.appendChild(chip);

            siswaResults.classList.add('hidden');
            siswaQuery.value = '';
        }

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        siswaSelected.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-chip')) {
                e.target.closest('[data-id]').remove();
            }
        });

        document.addEventListener('click', function (e) {
            if (!document.getElementById('siswaSearchContainer').contains(e.target)) {
                siswaResults.classList.add('hidden');
            }
        });

        toggleNamaTim();
        jenisPeserta.addEventListener('change', toggleNamaTim);

        // --- Live preview card ---
        const previewJudul = document.getElementById('previewJudul');
        const previewHasil = document.getElementById('previewHasil');
        const previewTingkat = document.getElementById('previewTingkat');
        const previewStatus = document.getElementById('previewStatus');

        document.getElementById('nama_lomba')?.addEventListener('input', function () {
            previewJudul.textContent = this.value || 'Judul prestasi akan tampil di sini';
        });
        document.getElementById('hasil')?.addEventListener('input', function () {
            previewHasil.textContent = this.value || 'Hasil';
        });
        document.getElementById('tingkat')?.addEventListener('change', function () {
            previewTingkat.textContent = this.value || 'Tingkat';
        });
        document.getElementById('status')?.addEventListener('change', function () {
            previewStatus.innerHTML = `<i class="fas fa-circle mr-1 text-[6px]"></i>${this.value}`;
            previewStatus.className = 'mt-3 text-[11px] font-semibold ' + (this.value === 'Publish' ? 'text-emerald-600' : 'text-slate-400');
        });

        // --- Foto preview (both inline + sidebar) ---
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('fotoPreview');
        const fotoPreviewIcon = document.getElementById('fotoPreviewIcon');
        const previewFoto = document.getElementById('previewFoto');
        const previewFotoIcon = document.getElementById('previewFotoIcon');

        fotoInput?.addEventListener('change', function () {
            const file = this.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                [fotoPreview, previewFoto].forEach(img => { img.src = e.target.result; img.classList.remove('hidden'); });
                [fotoPreviewIcon, previewFotoIcon].forEach(icon => icon.classList.add('hidden'));
            };
            reader.readAsDataURL(file);
        });

        // --- Keterangan counter ---
        const keterangan = document.getElementById('keterangan');
        const keteranganCount = document.getElementById('keteranganCount');
        function updateCount() { keteranganCount.textContent = `${keterangan.value.length}/500`; }
        keterangan?.addEventListener('input', updateCount);
        updateCount();
    });
</script>
