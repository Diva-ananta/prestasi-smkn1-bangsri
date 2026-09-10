<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Http\Requests\UpdateSiswaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SiswaExport;
use App\Models\DeletedRecord;
use App\Services\ImageOptimizationService;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status'); // 'Aktif', 'Alumni', or null (all)

        $query = Siswa::query();

        if ($request->filled('status') && in_array($request->status, ['Aktif', 'Alumni'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        $totalAll = Siswa::count();
        $totalAktif = Siswa::where('status', 'Aktif')->count();
        $totalAlumni = Siswa::where('status', 'Alumni')->count();

        $siswas = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('admin.siswa.index', compact('siswas', 'totalAll', 'totalAktif', 'totalAlumni', 'status'));
    }

    public function create()
    {
        $jenisKelamin = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
        $kelasOptions = ['10' => 'X', '11' => 'XI', '12' => 'XII'];
        $jurusanOptions = [
            'PPLG' => 'Pengembangan Perangkat Lunak dan Gim',
            'TO' => 'Teknik Otomotif',
            'MPLB' => 'Manajemen Perkantoran dan Layanan Bisnis',
            'AKL' => 'Akuntansi & Keuangan Lembaga',
            'PM' => 'Pemasaran',
        ];

        return view('admin.siswa.create', compact('jenisKelamin', 'kelasOptions', 'jurusanOptions'));
    }

    public function store(StoreSiswaRequest $request, ImageOptimizationService $imageOptimizer)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $imageOptimizer->storeSiswa($request->file('foto'));
        }

        // Angkatan otomatis jika kosong (tapi sudah required, jadi aman)
        Siswa::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Siswa berhasil ditambahkan ke database.', 'redirect' => route('admin.siswa.index')], 201);
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan ke database.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['detailPrestasi.prestasi']);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $jenisKelamin = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
        $kelasOptions = ['10' => 'X', '11' => 'XI', '12' => 'XII'];
        $jurusanOptions = [
            'PPLG' => 'Pengembangan Perangkat Lunak dan Gim',
            'MPLB' => 'Manajemen Perkantoran dan Layanan Bisnis',
            'TO' => 'Teknik Otomotif',
            'AKL' => 'Akuntansi & Keuangan Lembaga',
            'PM' => 'Pemasaran',
        ];

        return view('admin.siswa.edit', compact('siswa', 'jenisKelamin', 'kelasOptions', 'jurusanOptions'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa, ImageOptimizationService $imageOptimizer)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $newPhoto = $imageOptimizer->storeSiswa($request->file('foto'));
            if ($siswa->foto) Storage::disk('public')->delete($siswa->foto);
            $validated['foto'] = $newPhoto;
        }

        $siswa->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data siswa berhasil diperbarui.', 'redirect' => route('admin.siswa.index')]);
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        DeletedRecord::create([
            'model_type' => Siswa::class,
            'model_id' => $siswa->id,
            'snapshot' => ['data' => $siswa->getAttributes(), 'details' => $siswa->detailPrestasi()->get()->toArray()],
            'deleted_by' => auth()->id(), 'deleted_at' => now(),
        ]);
        $siswa->detailPrestasi()->delete();
        if ($siswa->foto) Storage::disk('public')->delete($siswa->foto);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil dihapus dari database.');
    }

    public function export(Request $request) { return Excel::download(new SiswaExport($request->input('ids', [])), 'data-siswa.xlsx'); }

    /**
     * Endpoint AJAX untuk pencarian siswa.
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $results = Siswa::where('nama', 'like', "%{$q}%")
            ->orWhere('nis', 'like', "%{$q}%")
            ->orWhere('nisn', 'like', "%{$q}%")
            ->orderBy('nama')
            ->limit(15)
            ->get(['id', 'nama', 'nis', 'kelas']);

        return response()->json($results);
    }
}