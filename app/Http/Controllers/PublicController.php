<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\Artikel;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $totalPrestasi = Prestasi::where('status', 'Publish')->count();
        $totalPrestasiTim = Prestasi::where('status', 'Publish')
            ->where('jenis_peserta', 'Tim')
            ->count();
        $totalSiswaBerprestasi = \App\Models\DetailPrestasi::distinct('siswa_id')->count('siswa_id');
        $totalSiswaAktif = Siswa::count();
        $tahunAktifMulai = now()->year - 3;
        $topSiswaAktif = Siswa::query()
            ->where('is_published', true)
            ->where('angkatan', '>=', $tahunAktifMulai)
            ->withCount(['detailPrestasi as prestasi_publish_count' => fn ($query) => $query->whereHas('prestasi', fn ($prestasiQuery) => $prestasiQuery->where('status', 'Publish'))])
            ->orderByDesc('prestasi_publish_count')
            ->orderBy('nama')
            ->take(4)
            ->get();
        $prestasiTerbaru = Prestasi::with('detailPrestasi.siswa')
            ->where('status', 'Publish')
            ->latest()
            ->take(4)
            ->get();
        $heroPrestasi = Prestasi::where('status', 'Publish')
            ->whereNotNull('foto')
            ->latest()
            ->first();
        $heroPrestasis = Prestasi::where('status', 'Publish')
            ->whereNotNull('foto')
            ->latest()
            ->take(5)
            ->get();
        $galeriPrestasi = Galeri::with('prestasi')
            ->where('is_published', true)
            ->take(6)
            ->get();

        return view('home', compact('totalPrestasi', 'totalPrestasiTim', 'totalSiswaBerprestasi', 'totalSiswaAktif', 'topSiswaAktif', 'prestasiTerbaru', 'heroPrestasi', 'heroPrestasis', 'galeriPrestasi'));
    }

    public function siswaSearch(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $tahunAktifMulai = now()->year - 3;
        $publishedSiswaQuery = Siswa::where('is_published', true);
        $siswas = Siswa::query()
            ->where('is_published', true)
            ->whereHas('detailPrestasi.prestasi', fn ($query) => $query->where('status', 'Publish'))
            ->when($keyword !== '', fn ($query) => $query->where(function ($query) use ($keyword) {
                $query->where('nis', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%")
                    ->orWhere('nama', 'like', "%{$keyword}%");
            }))
            ->when($request->filled('jurusan'), fn ($query) => $query->where('jurusan', $request->jurusan))
            ->when($request->filled('kelas'), fn ($query) => $query->where('kelas', $request->kelas))
            ->when($request->filled('angkatan'), fn ($query) => $query->where('angkatan', $request->angkatan))
            ->when($request->status === 'Aktif', fn ($query) => $query->where('angkatan', '>', $tahunAktifMulai))
            ->when($request->status === 'Alumni', fn ($query) => $query->where('angkatan', '<=', $tahunAktifMulai))
            ->withCount(['detailPrestasi as prestasi_publish_count' => fn ($query) => $query->whereHas('prestasi', fn ($prestasiQuery) => $prestasiQuery->where('status', 'Publish'))])
            ->orderByDesc('prestasi_publish_count')
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        $jurusanOptions = (clone $publishedSiswaQuery)->whereNotNull('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
        $kelasOptions = (clone $publishedSiswaQuery)->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $angkatanOptions = (clone $publishedSiswaQuery)->whereNotNull('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');

        $analitikSiswaJurusan = (clone $publishedSiswaQuery)
            ->select('jurusan', DB::raw('COUNT(*) as total'))
            ->whereNotNull('jurusan')
            ->groupBy('jurusan')
            ->orderByDesc('total')
            ->get();
        $analitikSiswaAngkatan = (clone $publishedSiswaQuery)
            ->select('angkatan', DB::raw('COUNT(*) as total'))
            ->whereNotNull('angkatan')
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->get();
        $analitikSiswaStatus = collect([
            ['status' => 'Aktif', 'total' => (clone $publishedSiswaQuery)->where('angkatan', '>', $tahunAktifMulai)->count()],
            ['status' => 'Alumni', 'total' => (clone $publishedSiswaQuery)->where('angkatan', '<=', $tahunAktifMulai)->count()],
        ]);

        return view('siswa.search', compact('siswas', 'keyword', 'jurusanOptions', 'kelasOptions', 'angkatanOptions', 'analitikSiswaJurusan', 'analitikSiswaAngkatan', 'analitikSiswaStatus'));
    }

    public function prestasiIndex(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $query = Prestasi::with('detailPrestasi.siswa')
            ->where('status', 'Publish')
            ->when($keyword !== '', fn ($query) => $query->where(function ($query) use ($keyword) {
                $query->where('nama_lomba', 'like', "%{$keyword}%")
                    ->orWhereHas('siswa', fn ($siswaQuery) => $siswaQuery
                        ->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('nis', 'like', "%{$keyword}%")
                        ->orWhere('nisn', 'like', "%{$keyword}%"));
            }));

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }
        if ($request->filled('jurusan')) {
            $query->whereHas('siswa', fn ($siswaQuery) => $siswaQuery->where('jurusan', $request->jurusan));
        }
        if ($request->filled('ekstrakurikuler')) {
            $query->where('nama_tim', $request->ekstrakurikuler);
        }

        $prestasis = $query->latest('tanggal_mulai')->paginate(12)->withQueryString();

        $kategoriOptions = Prestasi::publish()->distinct()->pluck('kategori')->filter();
        $tingkatOptions = Prestasi::publish()->distinct()->pluck('tingkat')->filter();
        $tahunOptions = Prestasi::publish()->selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->filter();

        $ekstrakurikulerOptions = array_values(array_unique(array_merge(
            array_keys(config('app.ekstrakurikuler', [])),
            Prestasi::publish()->whereNotNull('nama_tim')->distinct()->pluck('nama_tim')->all()
        )));
        $jurusanOptions = Siswa::query()->whereHas('detailPrestasi.prestasi', fn ($query) => $query->where('status', 'Publish'))->whereNotNull('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
        $analitikJurusan = DB::table('detail_prestasi')
            ->join('prestasi', 'prestasi.id', '=', 'detail_prestasi.prestasi_id')
            ->join('siswa', 'siswa.id', '=', 'detail_prestasi.siswa_id')
            ->where('prestasi.status', 'Publish')
            ->select('siswa.jurusan', DB::raw('COUNT(DISTINCT prestasi.id) as total'))
            ->groupBy('siswa.jurusan')
            ->orderByDesc('total')
            ->get();

        $topJurusan = $analitikJurusan->first();

        $topSiswa = DB::table('detail_prestasi')
            ->join('prestasi', 'prestasi.id', '=', 'detail_prestasi.prestasi_id')
            ->join('siswa', 'siswa.id', '=', 'detail_prestasi.siswa_id')
            ->where('prestasi.status', 'Publish')
            ->select('siswa.nama', 'siswa.kelas', 'siswa.jurusan', DB::raw('COUNT(DISTINCT prestasi.id) as total'))
            ->groupBy('siswa.id', 'siswa.nama', 'siswa.kelas', 'siswa.jurusan')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $analitikAngkatan = DB::table('detail_prestasi')->join('prestasi', 'prestasi.id', '=', 'detail_prestasi.prestasi_id')->join('siswa', 'siswa.id', '=', 'detail_prestasi.siswa_id')->where('prestasi.status', 'Publish')->select('siswa.angkatan', DB::raw('COUNT(DISTINCT prestasi.id) as total'))->groupBy('siswa.angkatan')->orderBy('siswa.angkatan')->get();
        $analitikTahun = Prestasi::publish()->selectRaw('YEAR(tanggal_mulai) as tahun, COUNT(*) as total')->whereNotNull('tanggal_mulai')->groupByRaw('YEAR(tanggal_mulai)')->orderBy('tahun')->get();
        $analitikRingkasan = [
            'total' => Prestasi::publish()->count(),
            'juara' => Prestasi::publish()->where(function ($query) {
                $query->where('hasil', 'like', '%juara%')->orWhere('hasil', 'like', '%1%')->orWhere('hasil', 'like', '%2%')->orWhere('hasil', 'like', '%3%');
            })->count(),
            'tingkat' => Prestasi::publish()->whereNotNull('tingkat')->distinct('tingkat')->count('tingkat'),
            'tahun' => Prestasi::publish()->whereNotNull('tanggal_mulai')->selectRaw('COUNT(DISTINCT YEAR(tanggal_mulai)) as total')->value('total'),
        ];
        $analitikTingkat = Prestasi::publish()->select('tingkat', DB::raw('COUNT(*) as total'))->whereNotNull('tingkat')->groupBy('tingkat')->orderByDesc('total')->get();
        $analitikJuara = Prestasi::publish()->select('hasil', DB::raw('COUNT(*) as total'))->whereNotNull('hasil')->groupBy('hasil')->orderByDesc('total')->limit(8)->get();
        $analitikPeriode = Prestasi::publish()->selectRaw('YEAR(tanggal_mulai) as tahun, MONTH(tanggal_mulai) as bulan, COUNT(*) as total')->whereNotNull('tanggal_mulai')->groupByRaw('YEAR(tanggal_mulai), MONTH(tanggal_mulai)')->orderByDesc('tahun')->orderByDesc('bulan')->limit(12)->get();
        $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        return view('prestasi.index', compact('prestasis', 'ekstrakurikulerOptions', 'kategoriOptions', 'tingkatOptions', 'tahunOptions', 'jurusanOptions', 'analitikJurusan', 'topJurusan', 'topSiswa', 'analitikAngkatan', 'analitikTahun', 'analitikRingkasan', 'analitikTingkat', 'analitikJuara', 'analitikPeriode', 'namaBulan'));
    }

    public function prestasiShow(string $token)
    {
        $prestasi = Prestasi::where('public_token', $token)->firstOrFail();
        // CEK: Jika status bukan Publish, tampilkan 404
        if ($prestasi->status !== 'Publish') {
            abort(404);
        }

        $prestasi->load([
            'detailPrestasi.siswa',
        ]);
        $siswaIds = $prestasi->detailPrestasi->pluck('siswa_id');
        $prestasi->setRelation('artikel', Artikel::publish()
            ->where(function ($query) use ($prestasi, $siswaIds) {
                $query->where('prestasi_id', $prestasi->id)
                    ->orWhereHas('prestasi.detailPrestasi', fn ($detailQuery) => $detailQuery->whereIn('siswa_id', $siswaIds));
            })
            ->latest('tanggal_publikasi')
            ->get());
        $prestasiTerkait = Prestasi::publish()
            ->where('id', '!=', $prestasi->id)
            ->whereHas('detailPrestasi', fn ($query) => $query->whereIn('siswa_id', $siswaIds))
            ->with('detailPrestasi.siswa')
            ->latest('tanggal_mulai')
            ->take(3)
            ->get();

        return view('prestasi.show', compact('prestasi', 'prestasiTerkait'));
    }

    public function siswaShow(?string $token = null)
    {
        $token ??= request()->query('token');
        $siswa = Siswa::where('public_token', $token)->firstOrFail();
        return $this->renderSiswaShow($siswa);
    }

    public function siswaShowByName(string $nama)
    {
        $slug = Str::slug($nama);
        $siswa = Siswa::where('is_published', true)
            ->get()
            ->first(fn (Siswa $candidate) => Str::slug($candidate->nama) === $slug);

        abort_unless($siswa, 404);

        return $this->renderSiswaShow($siswa);
    }

    private function renderSiswaShow(Siswa $siswa)
    {
        $prestasi = Prestasi::publish()
            ->whereHas('detailPrestasi', fn ($query) => $query->where('siswa_id', $siswa->id))
            ->with('detailPrestasi.siswa')
            ->latest('tanggal_mulai')
            ->get();
        $artikel = Artikel::publish()
            ->whereHas('prestasi.detailPrestasi', fn ($query) => $query->where('siswa_id', $siswa->id))
            ->with('prestasi')
            ->latest('tanggal_publikasi')
            ->get();

        return view('siswa.show', compact('siswa', 'prestasi', 'artikel'));
    }

    public function artikelIndex()
    {
        $artikels = Artikel::publish()->latest('tanggal_publikasi')->paginate(12);
        return view('artikel.index', compact('artikels'));
    }

    public function galeriIndex()
    {
        $galeri = Galeri::with('prestasi')
            ->where('is_published', true)
            ->paginate(12);

        return view('galeri.index', compact('galeri'));
    }

    public function artikelShow(string $artikel)
    {
        $artikelModel = Artikel::with('prestasi')->where('slug', $artikel)->first();

        if (!$artikelModel) {
            $requestedTokens = $this->articleSlugTokens($artikel);
            $artikelModel = Artikel::with('prestasi')->get()->first(function ($candidate) use ($requestedTokens) {
                $candidateTokens = $this->articleSlugTokens(
                    $candidate->slug . ' ' . ($candidate->prestasi?->nama_lomba ?? '') . ' ' . ($candidate->prestasi?->tingkat ?? '') . ' ' . ($candidate->prestasi?->hasil ?? '')
                );
                $overlap = count(array_intersect($requestedTokens, $candidateTokens));

                return $overlap >= 3;
            });
        }

        if (!$artikelModel || $artikelModel->status !== 'Publish' || ($artikelModel->tanggal_publikasi && $artikelModel->tanggal_publikasi > now())) {
            abort(404);
        }

        $artikelTerkait = Artikel::publish()
            ->where('id', '!=', $artikelModel->id)
            ->when($artikelModel->prestasi_id, function ($query) use ($artikelModel) {
                $query->where('prestasi_id', $artikelModel->prestasi_id);
            })
            ->latest('tanggal_publikasi')
            ->take(3)
            ->get();

        if ($artikelTerkait->isEmpty()) {
            $artikelTerkait = Artikel::publish()
                ->where('id', '!=', $artikelModel->id)
                ->latest('tanggal_publikasi')
                ->take(3)
                ->get();
        }

        return view('artikel.show', [
            'artikel' => $artikelModel,
            'artikelTerkait' => $artikelTerkait,
        ]);
    }

    private function articleSlugTokens(string $value): array
    {
        $slug = Str::slug($value);
        $slug = str_replace(['riding', 'competition'], ['ridding', 'kompetisi'], $slug);

        return array_values(array_unique(array_filter(explode('-', $slug), fn ($token) => strlen($token) > 2)));
    }

    public function tentang()
    {
        return view('tentang');
    }
}