<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Artikel::with('prestasi')
            ->publish()
            ->whereIn('id', Artikel::query()
                ->selectRaw('MAX(id)')
                ->groupBy('slug')
            )
            ->latest('tanggal_publikasi');

        /*
        |--------------------------------------------------------------------------
        | Pencarian
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter tahun
        |--------------------------------------------------------------------------
        */
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_publikasi', $request->tahun);
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $artikels = $query->paginate(12);

        /*
        |--------------------------------------------------------------------------
        | Format response
        |--------------------------------------------------------------------------
        */
        $data = $artikels->getCollection()->map(function ($artikel) {
            return [
                'judul' => $artikel->judul,
                'slug' => $artikel->slug,

                'isi' => $artikel->isi,

                'gambar_url' => $artikel->gambar
                    ? asset('storage/' . $artikel->gambar)
                    : null,

                'penulis' => $artikel->penulis,

                'tanggal_publikasi' => $artikel->tanggal_publikasi?->format('Y-m-d'),

                'status' => $artikel->status,

                'prestasi' => $artikel->prestasi ? [
                    'public_token' => $artikel->prestasi->public_token,
                    'nama_lomba' => $artikel->prestasi->nama_lomba,
                    'hasil' => $artikel->prestasi->hasil,
                    'detail_url' => route(
                        'public.prestasi.show',
                        $artikel->prestasi->public_token
                    ),
                ] : null,

                'detail_url' => route(
                    'public.artikel.show',
                    $artikel->slug
                ),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data->values(),

            'pagination' => [
                'current_page' => $artikels->currentPage(),
                'per_page' => $artikels->perPage(),
                'total' => $artikels->total(),
                'last_page' => $artikels->lastPage(),
                'from' => $artikels->firstItem(),
                'to' => $artikels->lastItem(),
            ],
        ]);
    }
}