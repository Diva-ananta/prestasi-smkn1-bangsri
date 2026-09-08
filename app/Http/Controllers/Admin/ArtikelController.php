<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\DeletedRecord;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::with('prestasi')
            ->whereIn('id', Artikel::query()->selectRaw('MAX(id)')->groupBy('slug'))
            ->latest()
            ->paginate(10);
        $prestasis = Prestasi::orderBy('nama_lomba')->get();
        return view('admin.artikel.index', compact('artikels', 'prestasis'));
    }

    public function create()
    {
        $artikels = Artikel::with('prestasi')
            ->whereIn('id', Artikel::query()->selectRaw('MAX(id)')->groupBy('slug'))
            ->latest()
            ->paginate(10);
        $prestasis = Prestasi::orderBy('nama_lomba')->get();
        return view('admin.artikel.index', compact('artikels', 'prestasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'penulis' => 'nullable|string|max:100',
            'tanggal_publikasi' => 'nullable|date',
            'prestasi_id' => 'nullable|exists:prestasi,id',
            'status' => 'required|in:Draft,Publish',
        ]);

        $data = $request->only([
            'judul', 'isi', 'penulis', 'tanggal_publikasi', 'prestasi_id', 'status',
        ]);

        $data['slug'] = $this->uniqueSlug($request->judul);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('artikel', 'public');
        } elseif (!empty($data['prestasi_id'])) {
            $data['gambar'] = Prestasi::find($data['prestasi_id'])?->foto;
        }

        // Jika tanggal publikasi kosong, isi dengan hari ini
        if (empty($data['tanggal_publikasi'])) {
            $data['tanggal_publikasi'] = now();
        }

        Artikel::create($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Artikel baru berhasil ditambahkan.', 'redirect' => route('admin.artikel.index')], 201);
        }

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel baru berhasil ditambahkan.');
    }

    public function show(Artikel $artikel)
    {
        $artikel->load('prestasi');
        return view('admin.artikel.show', compact('artikel'));
    }

    public function edit(Artikel $artikel)
    {
        $artikels = Artikel::with('prestasi')
            ->whereIn('id', Artikel::query()->selectRaw('MAX(id)')->groupBy('slug'))
            ->latest()
            ->paginate(10);
        $prestasis = Prestasi::orderBy('nama_lomba')->get();
        return view('admin.artikel.index', compact('artikels', 'prestasis', 'artikel'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'penulis' => 'nullable|string|max:100',
            'tanggal_publikasi' => 'nullable|date',
            'prestasi_id' => 'nullable|exists:prestasi,id',
            'status' => 'required|in:Draft,Publish',
        ]);

        $data = $request->only([
            'judul', 'isi', 'penulis', 'tanggal_publikasi', 'prestasi_id', 'status',
        ]);

        // Update slug jika judul berubah
        if ($artikel->judul != $request->judul) {
            $data['slug'] = $this->uniqueSlug($request->judul, $artikel->id);
        }

        // Upload gambar baru
        if ($request->hasFile('gambar')) {
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('artikel', 'public');
        } elseif (!empty($data['prestasi_id']) && $data['prestasi_id'] != $artikel->prestasi_id) {
            $data['gambar'] = Prestasi::find($data['prestasi_id'])?->foto;
        }

        $data['prestasi_id'] = $data['prestasi_id'] ?? null;

        $artikel->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data artikel berhasil diperbarui.', 'redirect' => route('admin.artikel.index')]);
        }

        return redirect()->route('admin.artikel.index')->with('success', 'Data artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        DeletedRecord::create([
            'model_type' => Artikel::class,
            'model_id' => $artikel->id,
            'snapshot' => ['data' => $artikel->getAttributes(), 'details' => []],
            'deleted_by' => auth()->id(), 'deleted_at' => now(),
        ]);
        if ($artikel->gambar) {
            Storage::disk('public')->delete($artikel->gambar);
        }
        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus dari database.');
    }

    private function uniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($judul) ?: 'artikel';
        $slug = $baseSlug;
        $counter = 1;

        while (Artikel::where('slug', $slug)
              ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}