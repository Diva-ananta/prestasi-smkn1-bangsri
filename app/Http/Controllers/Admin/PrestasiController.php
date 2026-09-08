<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\DetailPrestasi;
use App\Http\Requests\StorePrestasiRequest;
use App\Http\Requests\UpdatePrestasiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrestasiExport;
use App\Models\DeletedRecord;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prestasi::query();

        // Pencarian berdasarkan nama lomba, hasil, kategori, atau tingkat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lomba', 'like', "%{$search}%")
                  ->orWhere('hasil', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%");
            });
        }

        $prestasis = $query->select([
            'id',
            'nama_lomba',
            'jenis_peserta',
            'hasil',
            'status',
            'created_at',
        ])->latest()->paginate(10)->withQueryString();

        // Data siswa untuk form modal (pencarian akan pakai AJAX)
        $siswas = \App\Models\Siswa::orderBy('nama')->get();

        return view('admin.prestasi.index', compact('prestasis', 'siswas'));
    }

    public function create()
    {
        $siswas = Siswa::orderBy('nama')->get();
        return view('admin.prestasi.create', compact('siswas'));
    }
   
    public function store(StorePrestasiRequest $request)
    {
        $data = $request->validated();
        $siswaIds = $data['siswa_id'];
        unset($data['siswa_id']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-prestasi', 'public');
        }

        $prestasi = Prestasi::create($data);

        // Simpan relasi siswa
        foreach ($siswaIds as $siswaId) {
            DetailPrestasi::create([
                'prestasi_id' => $prestasi->id,
                'siswa_id' => $siswaId,
                'peran' => $request->peran ?? 'Anggota',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Prestasi baru berhasil ditambahkan.', 'redirect' => route('admin.prestasi.index')], 201);
        }

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi baru berhasil ditambahkan.');
    }
    public function edit(Prestasi $prestasi)
    {
        $siswas = Siswa::orderBy('nama')->get();
        $selectedSiswa = $prestasi->detailPrestasi->pluck('siswa_id')->toArray();
        return view('admin.prestasi.edit', compact('prestasi', 'siswas', 'selectedSiswa'));
    }

    public function update(UpdatePrestasiRequest $request, Prestasi $prestasi)
    {
        $data = $request->validated();
        $siswaIds = $data['siswa_id'];
        unset($data['siswa_id']);

        if ($request->hasFile('foto')) {
            if ($prestasi->foto) {
                Storage::disk('public')->delete($prestasi->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-prestasi', 'public');
        }

        $prestasi->update($data);

        // Update relasi siswa: hapus yang lama, tambah yang baru
        DetailPrestasi::where('prestasi_id', $prestasi->id)->delete();
        foreach ($siswaIds as $siswaId) {
            DetailPrestasi::create([
                'prestasi_id' => $prestasi->id,
                'siswa_id' => $siswaId,
                'peran' => $request->peran ?? 'Anggota',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data prestasi berhasil diperbarui.', 'redirect' => route('admin.prestasi.index')]);
        }

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        DeletedRecord::create([
            'model_type' => Prestasi::class,
            'model_id' => $prestasi->id,
            'snapshot' => ['data' => $prestasi->getAttributes(), 'details' => $prestasi->detailPrestasi()->get()->toArray()],
            'deleted_by' => auth()->id(), 'deleted_at' => now(),
        ]);
        // Hapus file foto jika ada
        if ($prestasi->foto) {
            Storage::disk('public')->delete($prestasi->foto);
        }

        // Hapus data dari database
        $prestasi->delete();

        return back()->with('success', 'Prestasi berhasil dihapus dari database.');
    }

    public function export(Request $request) { return Excel::download(new PrestasiExport($request->input('ids', [])), 'data-prestasi.xlsx'); }

}