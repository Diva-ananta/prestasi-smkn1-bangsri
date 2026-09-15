<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Galeri::with('prestasi')->latest()->paginate(12);
        $usedPrestasiIds = Galeri::whereNotNull('prestasi_id')->pluck('prestasi_id');
        $prestasis = Prestasi::publish()
            ->where(function ($query) {
                $query->whereNotNull('foto')->orWhereNotNull('video_url');
            })
            ->whereNotIn('id', $usedPrestasiIds)
            ->latest('tanggal_mulai')
            ->get(['id', 'nama_lomba', 'hasil', 'foto', 'video_url']);

        return view('admin.galeri.index', compact('galeri', 'prestasis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'nullable|string|max:255',
            'prestasi_id' => [
                'nullable',
                Rule::exists('prestasi', 'id')->where(fn ($query) => $query->where('status', 'Publish')),
                Rule::unique('galeri', 'prestasi_id'),
            ],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'video_url' => ['nullable', 'url', 'max:2048', function ($attribute, $value, $fail) {
                $host = preg_replace('/^www\./', '', strtolower((string) parse_url($value, PHP_URL_HOST)));
                if ($host !== 'youtube.com' && $host !== 'youtu.be' && ! str_ends_with($host, '.youtube.com')) {
                    $fail('Link video harus berasal dari YouTube.');
                }
            }],
        ]);

        if (! empty($data['prestasi_id'])) {
            $prestasi = Prestasi::publish()->findOrFail($data['prestasi_id']);
            $data['foto'] = $prestasi->foto;
            $data['video_url'] = $prestasi->video_url;
        } else {
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('galeri', 'public');
            }

            if (empty($data['foto']) && empty($data['video_url'])) {
                return back()->withInput()->withErrors(['media' => 'Tambahkan foto atau link YouTube untuk galeri.']);
            }
        }

        Galeri::create($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function toggleVisibility(Galeri $galeri)
    {
        $galeri->update(['is_published' => ! $galeri->is_published]);

        return back()->with('success', $galeri->is_published
            ? 'Foto galeri ditampilkan di halaman publik.'
            : 'Foto galeri disembunyikan dari halaman publik.');
    }

    public function destroy(Galeri $galeri)
    {
        if (! $galeri->prestasi_id && $galeri->foto) {
            Storage::disk('public')->delete($galeri->foto);
        }
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil dihapus.');
    }
}
