<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $query = Artikel::query()
            ->where('status', 'Publish')
            ->where('tanggal_publikasi', '<=', now())
            ->orderByDesc('tanggal_publikasi')
            ->orderByDesc('id');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tahun
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['tahun'])) {
            $query->whereYear(
                'tanggal_publikasi',
                $validated['tahun']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $artikel = $query
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Format Response
        |--------------------------------------------------------------------------
        */

        $data = $artikel->getCollection()->map(function ($item) {

            $gambarUrl = $item->gambar
                ? asset('storage/' . ltrim($item->gambar, '/'))
                : null;

            return [
                'judul' => $item->judul,
                'slug' => $item->slug,
                'isi' => $item->isi,
                'gambar_url' => $gambarUrl,
                'penulis' => $item->penulis,
                'tanggal_publikasi' => $item->tanggal_publikasi
                    ? $item->tanggal_publikasi->format('Y-m-d')
                    : null,

                'status' => $item->status,

                'prestasi_id' => $item->prestasi_id,

                'detail_url' => $this->getDetailUrl($item),
            ];
        });

        return response()->json([
            'success' => true,

            'data' => $data,

            'pagination' => [
                'current_page' => $artikel->currentPage(),
                'per_page' => $artikel->perPage(),
                'total' => $artikel->total(),
                'last_page' => $artikel->lastPage(),
                'from' => $artikel->firstItem(),
                'to' => $artikel->lastItem(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $artikel = Artikel::query()
            ->where('slug', $slug)
            ->where('status', 'Publish')
            ->where('tanggal_publikasi', '<=', now())
            ->first();

        if (!$artikel) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan.',
                'error_code' => 'ARTICLE_NOT_FOUND',
            ], 404);
        }

        $gambarUrl = $artikel->gambar
            ? asset('storage/' . ltrim($artikel->gambar, '/'))
            : null;

        return response()->json([
            'success' => true,

            'data' => [
                'judul' => $artikel->judul,
                'slug' => $artikel->slug,
                'isi' => $artikel->isi,
                'gambar_url' => $gambarUrl,
                'penulis' => $artikel->penulis,

                'tanggal_publikasi' => $artikel->tanggal_publikasi
                    ? $artikel->tanggal_publikasi->format('Y-m-d')
                    : null,

                'status' => $artikel->status,
                'prestasi_id' => $artikel->prestasi_id,

                'detail_url' => $this->getDetailUrl($artikel),
            ],
        ]);
    }

    private function getDetailUrl(Artikel $artikel): ?string
    {
        try {
            return route('public.artikel.show', $artikel->slug);
        } catch (\Throwable $e) {
            return null;
        }
    }
}