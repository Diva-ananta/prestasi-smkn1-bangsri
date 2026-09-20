<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'ekstrakurikuler' => ['nullable', 'string', 'max:150'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $search = trim($request->query('search', ''));
        $jurusan = trim($request->query('jurusan', ''));
        $ekstrakurikuler = trim($request->query('ekstrakurikuler', ''));
        $tahun = $request->query('tahun');

        /*
        |--------------------------------------------------------------------------
        | Query utama prestasi
        |--------------------------------------------------------------------------
        */

        $query = DB::table('prestasi')
            ->where(
                'prestasi.status',
                config('services.sipres_api.public_status', 'Publish')
            );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $like = '%' . $search . '%';

                $q->where('prestasi.nama_lomba', 'like', $like)
                    ->orWhere('prestasi.kategori', 'like', $like)
                    ->orWhere('prestasi.tingkat', 'like', $like)
                    ->orWhere('prestasi.penyelenggara', 'like', $like)
                    ->orWhere('prestasi.hasil', 'like', $like)
                    ->orWhere('prestasi.nama_tim', 'like', $like)
                    ->orWhereExists(function ($subQuery) use ($like) {
                        $subQuery->selectRaw('1')
                            ->from('detail_prestasi')
                            ->join(
                                'siswa',
                                'siswa.id',
                                '=',
                                'detail_prestasi.siswa_id'
                            )
                            ->whereColumn(
                                'detail_prestasi.prestasi_id',
                                'prestasi.id'
                            )
                            ->where('siswa.is_published', 1)
                            ->where('siswa.nama', 'like', $like);
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter jurusan
        |--------------------------------------------------------------------------
        */

        if ($jurusan !== '') {
            $query->whereExists(function ($subQuery) use ($jurusan) {
                $subQuery->selectRaw('1')
                    ->from('detail_prestasi')
                    ->join(
                        'siswa',
                        'siswa.id',
                        '=',
                        'detail_prestasi.siswa_id'
                    )
                    ->whereColumn(
                        'detail_prestasi.prestasi_id',
                        'prestasi.id'
                    )
                    ->where('siswa.jurusan', $jurusan)
                    ->where('siswa.is_published', 1);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter ekstrakurikuler
        |--------------------------------------------------------------------------
        */

        if ($ekstrakurikuler !== '') {
            $query->where(
                'prestasi.nama_tim',
                $ekstrakurikuler
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter tahun
        |--------------------------------------------------------------------------
        */

        if ($tahun) {
            $query->whereYear(
                'prestasi.tanggal_mulai',
                $tahun
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        | Selalu 12 data per halaman
        |--------------------------------------------------------------------------
        */

        $prestasi = $query
            ->orderByDesc('prestasi.tanggal_mulai')
            ->orderByDesc('prestasi.id')
            ->paginate(12);

        /*
        |--------------------------------------------------------------------------
        | Ambil peserta
        |--------------------------------------------------------------------------
        */

        $prestasiIds = $prestasi->getCollection()
            ->pluck('id');

        $pesertaByPrestasi = collect();

        if ($prestasiIds->isNotEmpty()) {
            $pesertaQuery = DB::table('detail_prestasi')
                ->join(
                    'siswa',
                    'siswa.id',
                    '=',
                    'detail_prestasi.siswa_id'
                )
                ->whereIn(
                    'detail_prestasi.prestasi_id',
                    $prestasiIds
                )
                ->where('siswa.is_published', 1)
                ->select([
                    'detail_prestasi.prestasi_id',
                    'siswa.public_token',
                    'siswa.nama',
                    'siswa.jurusan',
                    'siswa.kelas',
                    'detail_prestasi.peran',
                ])
                ->orderBy('siswa.nama');

            /*
            |--------------------------------------------------------------------------
            | Jika API difilter jurusan,
            | peserta juga hanya dari jurusan tersebut
            |--------------------------------------------------------------------------
            */

            if ($jurusan !== '') {
                $pesertaQuery->where(
                    'siswa.jurusan',
                    $jurusan
                );
            }

            $pesertaByPrestasi = $pesertaQuery
                ->get()
                ->groupBy('prestasi_id');
        }

        /*
        |--------------------------------------------------------------------------
        | Bentuk response API
        |--------------------------------------------------------------------------
        */

        $data = $prestasi->getCollection()
            ->map(function ($item) use ($pesertaByPrestasi) {

                /*
                |--------------------------------------------------------------------------
                | Data ekstrakurikuler
                |--------------------------------------------------------------------------
                */

                $ekstrakurikulerOptions = config(
                    'app.ekstrakurikuler',
                    []
                );

                $ekstrakurikulerNama = trim(
                    $item->nama_tim ?? ''
                );

                $ekstrakurikulerUrl = $ekstrakurikulerNama !== ''
                    ? (
                        $ekstrakurikulerOptions[
                            $ekstrakurikulerNama
                        ] ?? null
                    )
                    : null;

                /*
                |--------------------------------------------------------------------------
                | Data peserta
                |--------------------------------------------------------------------------
                */

                $peserta = $pesertaByPrestasi
                    ->get($item->id, collect())
                    ->map(function ($siswa) {
                        return [
                            'nama' => $siswa->nama,
                            'jurusan' => $siswa->jurusan,
                            'kelas' => $siswa->kelas,
                            'peran' => $siswa->peran,
                        ];
                    })
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | Data prestasi
                |--------------------------------------------------------------------------
                */

                return [
                    'nama_lomba' => $item->nama_lomba,

                    'kategori' => $item->kategori,

                    'ekstrakurikuler' => $ekstrakurikulerNama !== ''
                        ? [
                            'nama' => $ekstrakurikulerNama,
                            'url' => $ekstrakurikulerUrl,
                        ]
                        : null,

                    'tingkat' => $item->tingkat,

                    'penyelenggara' => $item->penyelenggara,

                    'tanggal_mulai' => $item->tanggal_mulai,

                    'tanggal_selesai' => $item->tanggal_selesai,

                    'lokasi' => $item->lokasi,

                    'bidang_lomba' => $item->bidang_lomba,

                    'hasil' => $item->hasil,

                    'kategori_juara' => $item->kategori_juara,

                    'foto_url' => $item->foto
                        ? asset(
                            'storage/' .
                            ltrim($item->foto, '/')
                        )
                        : null,

                    'detail_url' => route(
                        'public.prestasi.show',
                        $item->public_token
                    ),

                    'peserta' => $peserta,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'data' => $data,

            'pagination' => [
                'current_page' => $prestasi->currentPage(),

                'per_page' => $prestasi->perPage(),

                'total' => $prestasi->total(),

                'last_page' => $prestasi->lastPage(),

                'from' => $prestasi->firstItem(),

                'to' => $prestasi->lastItem(),
            ],
        ]);
    }
}