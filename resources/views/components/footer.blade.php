<footer id="kontak" class="border-t border-slate-800 bg-slate-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-2 lg:grid-cols-[1.1fr_1fr_1fr_1.35fr] lg:px-8">
        <div class="lg:col-span-1">
            <div class="flex items-center gap-3">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white p-1.5 shadow-sm">
                    <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK Negeri 1 Bangsri" class="h-full w-full object-contain">
                </div>
                <div>
                    <p class="font-bold">SIPRES ESKASABA</p>
                    <p class="text-xs text-slate-300">Sistem Informasi Prestasi Siswa</p>
                </div>
            </div>
            <p class="mt-4 max-w-sm text-sm leading-6 text-slate-300">Dokumentasi pencapaian siswa yang tertata, mudah ditemukan, dan dapat diakses oleh seluruh keluarga sekolah.</p>
            <div class="mt-5 flex items-center gap-3" aria-label="Media sosial SMK Negeri 1 Bangsri">
                <a href="https://www.instagram.com/smkn1bangsri.official?utm_source=ig_web_button_share_sheet&stkn=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-md bg-white/10 transition hover:bg-emerald-700" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                <a href="https://youtube.com/@smkn1bangsri?si=8bC8yFdWzTy6Thdo" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-md bg-white/10 transition hover:bg-emerald-700" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
                <a href="https://www.tiktok.com/@smkn1bangsri.official?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-md bg-white/10 transition hover:bg-emerald-700" aria-label="TikTok"><i class="fab fa-tiktok" aria-hidden="true"></i></a>
                <a href="https://smkn1bangsri.sch.id/" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-md bg-white/10 transition hover:bg-emerald-700" aria-label="Website Sekolah" title="Website Sekolah"><i class="fas fa-globe" aria-hidden="true"></i></a>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-white">Menu Utama</h2>
            <div class="mt-4 space-y-2 text-sm text-slate-300">
                <a href="{{ route('home') }}" class="block transition hover:text-white">Prestasi</a>
                <a href="{{ route('public.prestasi.index') }}" class="block transition hover:text-emerald-300">Data Prestasi</a>
                <a href="{{ route('public.siswa.search') }}" class="block transition hover:text-white">Siswa Beprestasi</a>
                <a href="{{ route('public.artikel.index') }}" class="block transition hover:text-white">Artikel</a>
                <a href="{{ route('public.tentang') }}" class="block transition hover:text-emerald-300">Tentang</a>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-white">Kontak</h2>
            <div class="mt-4 space-y-3 text-sm text-slate-300">
                <a href="tel:0291772321" class="flex items-center gap-3 transition hover:text-emerald-300"><i class="fas fa-phone-alt w-4 text-center" aria-hidden="true"></i><span>(0291) 772321</span></a>
                <a href="mailto:smkn1bangsri@yahoo.co.id" class="flex items-center gap-3 break-words transition hover:text-emerald-300"><i class="fas fa-envelope w-4 shrink-0 text-center" aria-hidden="true"></i><span>smkn1bangsri@yahoo.co.id</span></a>
            </div>
        </div>
        <div>
            <h2 class="text-lg font-bold text-white">Maps</h2>
            <div class="mt-4 overflow-hidden rounded-lg border border-white/10 bg-slate-900">
                <iframe title="Lokasi SMK Negeri 1 Bangsri" src="https://maps.google.com/maps?q=SMK%20Negeri%201%20Bangsri%2C%20Jepara&z=15&output=embed" class="h-48 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-4 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>&copy; {{ date('Y') }} SMK Negeri 1 Bangsri. Seluruh hak cipta dilindungi.</p>
            <div class="space-y-1 sm:text-right">
                <p>
                    Dikembangkan oleh <span class="font-semibold text-slate-300">Akasa Dev</span>:
                    <a href="https://www.instagram.com/askiakhoirunnisa/" target="_blank" rel="noopener noreferrer" class="transition hover:text-white hover:underline">Askia Khoirun Nisa</a>,
                    <a href="https://www.instagram.com/ekakusnaini/" target="_blank" rel="noopener noreferrer" class="transition hover:text-white hover:underline">Eka Kusnaini</a>,
                    <a href="https://www.instagram.com/khnsaskiaa/" target="_blank" rel="noopener noreferrer" class="transition hover:text-white hover:underline">Saka Diva Ananta</a>
                </p>
            </div>
        </div>
    </div>
</footer>