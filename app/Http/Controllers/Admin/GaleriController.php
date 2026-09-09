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
            ->whereNotNull('foto')
            ->whereNotIn('id', $usedPrestasiIds)
            ->latest('tanggal_mulai')
            ->get(['id', 'nama_lomba', 'hasil', 'foto']);

        return view('admin.galeri.index', compact('galeri', 'prestasis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'nullable|string|max:255',
            'prestasi_id' => [
                'required',
                Rule::exists('prestasi', 'id')->where(fn ($query) => $query->where('status', 'Publish')->whereNotNull('foto')),
                Rule::unique('galeri', 'prestasi_id'),
            ],
        ]);

        $prestasi = Prestasi::publish()->findOrFail($data['prestasi_id']);
        $data['foto'] = $prestasi->foto;
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
        if (! $galeri->prestasi_id) {
            Storage::disk('public')->delete($galeri->foto);
        }
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil dihapus.');
    }
}
