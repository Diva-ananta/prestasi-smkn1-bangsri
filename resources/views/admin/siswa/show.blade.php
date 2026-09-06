<x-app-layout>
    <div class="mx-auto max-w-6xl space-y-6">
        <x-admin.breadcrumb :items="['Siswa' => route('admin.siswa.index'), 'Detail' => '#']" />

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Data Siswa</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-800 dark:text-white">Profil dan Riwayat Prestasi</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <a data-ajax-page href="{{ route('admin.siswa.edit', $siswa) }}" title="Edit siswa" aria-label="Edit siswa" class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700 transition hover:bg-amber-200"><i class="fas fa-pen"></i></a>
                <a href="{{ route('admin.siswa.index') }}" title="Kembali ke daftar siswa" aria-label="Kembali ke daftar siswa" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"><i class="fas fa-arrow-left"></i></a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
            <aside class="h-fit overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="relative h-20 bg-emerald-700">
                    <div class="absolute -bottom-10 left-1/2 flex h-20 w-20 -translate-x-1/2 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-emerald-50 text-2xl font-bold text-emerald-700 shadow-md dark:border-slate-900 dark:bg-emerald-950 dark:text-emerald-300">
                        @if($siswa->foto)
                            <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="h-full w-full object-cover">
                        @else
                            {{ collect(explode(' ', $siswa->nama))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->join('') }}
                        @endif
                    </div>
                </div>
                <div class="px-5 pb-5 pt-14 text-center">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ $siswa->nama }}</h2>
                    <p class="mt-1 text-sm text-emerald-700 dark:text-emerald-400">NIS: {{ $siswa->nis }}</p>
                    <div class="my-5 border-t border-slate-200 dark:border-slate-700"></div>
                    <div class="grid grid-cols-2 gap-4 text-left text-xs">
                        <div><p class="text-slate-400">Kelas</p><p class="mt-1 font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->kelas }}</p></div>
                        <div><p class="text-slate-400">Jurusan</p><p class="mt-1 font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->jurusan }}</p></div>
                        <div><p class="text-slate-400">Angkatan</p><p class="mt-1 font-semibold text-slate-700 dark:text-slate-200">{{ $siswa->angkatan }}</p></div>
                        <div><p class="text-slate-400">Status</p><p class="mt-1 font-semibold text-emerald-700 dark:text-emerald-400">{{ $siswa->status }}</p></div>
                    </div>
                </div>
            </aside>

            <section>
                <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-700">
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-800 dark:text-white"><i class="fas fa-trophy text-emerald-600"></i> Riwayat Prestasi</h2>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ $siswa->detailPrestasi->count() }} prestasi</span>
                </div>

                @forelse($siswa->detailPrestasi as $detail)
                    @php $prestasi = $detail->prestasi; @endphp
                    @if($prestasi)
                        <article class="mb-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300"><i class="fas fa-award"></i></div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div><div class="mb-1 flex flex-wrap gap-2"><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $prestasi->hasil }}</span><span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $prestasi->status === 'Publish' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' }}">{{ $prestasi->status }}</span></div><h3 class="font-bold text-slate-800 dark:text-white">{{ $prestasi->nama_lomba }}</h3></div>
                                        <div class="text-right text-xs text-slate-400">Tahun<br><strong class="text-sm text-slate-700 dark:text-slate-200">{{ $prestasi->tanggal_mulai ? \Carbon\Carbon::parse($prestasi->tanggal_mulai)->format('Y') : $prestasi->created_at->format('Y') }}</strong></div>
                                    </div>
                                    <p class="mt-1 text-sm leading-5 text-slate-500 dark:text-slate-400">{{ $prestasi->keterangan ?? 'Prestasi siswa pada kompetisi ' . ($prestasi->tingkat ?? 'umum') . '.' }}</p>
                                    @if($detail->peran)<p class="mt-1 text-xs text-slate-400">Peran: {{ $detail->peran }}</p>@endif
                                    <a data-ajax-page href="{{ route('admin.prestasi.edit', $prestasi) }}" title="Edit prestasi" aria-label="Edit prestasi" class="mt-2 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-300"><i class="fas fa-pen text-xs"></i></a>
                                </div>
                            </div>
                        </article>
                    @endif
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">Siswa ini belum memiliki prestasi.</div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
