<footer id="kontak" class="border-t border-slate-800 bg-slate-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-9 sm:px-6 md:grid-cols-2 lg:grid-cols-[1.1fr_1fr_1fr_1.35fr] lg:px-8">
        <div class="lg:col-span-1">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK Negeri 1 Bangsri" class="h-11 w-11 rounded-full bg-white p-1 object-contain">
                <div>
                    <p class="font-bold">SIPRES ESKASABA</p>
                    <p class="text-xs text-slate-300">Sistem Informasi Prestasi Siswa</p>
                </div>
            </div>
            <p class="mt-4 max-w-sm text-sm leading-6 text-slate-300">Dokumentasi pencapaian siswa yang tertata, mudah ditemukan, dan dapat diakses oleh seluruh keluarga sekolah.</p>
            <a href="https://smkn1bangsri.sch.id/" target="_blank" rel="noopener noreferrer" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600">
                <i class="fas fa-globe"></i> Website Sekolah
            </a>
            <div class="mt-5 flex items-center gap-3">
                <a href="https://www.instagram.com/smkn1bangsri.official?utm_source=ig_web_button_share_sheet&stkn=ZDNlZDc0MzIxNw==" class="flex h-9 w-9 items-center justify-center rounded-md bg-white/10 transition hover:bg-emerald-700" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://youtube.com/@smkn1bangsri?si=8bC8yFdWzTy6Thdo" class="flex h-9 w-9 items-center justify-center rounded-md bg-white/10 transition hover:bg-emerald-700" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="https://www.tiktok.com/@smkn1bangsri.official?is_from_webapp=1&sender_device=pc" class="flex h-9 w-9 items-center justify-center rounded-md bg-white/10 transition hover:bg-emerald-700" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>

        <div>   
            <h2 class="text-lg font-bold text-white">Menu Utama</h2>
            <div class="mt-4 space-y-2 text-sm text-slate-300">
                <a href="{{ route('home') }}" class="block transition hover:text-white">Beranda</a>
                <a href="{{ route('public.prestasi.index') }}" class="block transition hover:text-emerald-300">Prestasi</a>
                <a href="{{ route('public.artikel.index') }}" class="block transition hover:text-white">Artikel</a>
                <a href="{{ route('public.tentang') }}" class="block transition hover:text-emerald-300">Tentang</a>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-white">Kontak</h2>
            <div class="mt-4 space-y-3 text-sm text-slate-300">
                <a href="tel:0291772321" class="block transition hover:text-emerald-300">(0291) 772321</a>
                <a href="mailto:smkn1bangsri@yahoo.co.id" class="block break-words transition hover:text-emerald-300">smkn1bangsri@yahoo.co.id</a>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-white">Maps</h2>
            <div class="mt-4 overflow-hidden rounded-lg border border-white/10 bg-slate-900">
                <iframe title="Lokasi SMK Negeri 1 Bangsri" src="https://www.google.com/maps?q=SMK+Negeri+1+Bangsri&output=embed" class="h-48 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-4 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>&copy; {{ date('Y') }} SMK Negeri 1 Bangsri. Seluruh hak cipta dilindungi.</p>
            <p>Portal Prestasi Siswa</p>
        </div>
    </div>
</footer>