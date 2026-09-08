<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Galeri::latest()->paginate(12);

        return view('admin.galeri.index', compact('galeri'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'nullable|string|max:255',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data['foto'] = $request->file('foto')->store('galeri', 'public');
        Galeri::create($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function destroy(Galeri $galeri)
    {
        Storage::disk('public')->delete($galeri->foto);
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil dihapus.');
    }
}
