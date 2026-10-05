<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Http\Requests\UpdateSiswaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DeletedRecord;
use App\Services\ImageOptimizationService;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:Aktif,Alumni'],
            'kelas' => ['nullable', 'string', 'max:10'],
            'jurusan' => ['nullable', 'string', 'max:50'],
            'angkatan' => ['nullable', 'integer', 'min:2000', 'max:' . now()->year],
        ]);

        $query = Siswa::query()
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['kelas'] ?? null, fn ($query, $kelas) => $query->where('kelas', $kelas))
            ->when($filters['jurusan'] ?? null, fn ($query, $jurusan) => $query->where('jurusan', $jurusan))
            ->when($filters['angkatan'] ?? null, fn ($query, $angkatan) => $query->where('angkatan', $angkatan))
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nis', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('kelas', 'like', "%{$search}%")
                        ->orWhere('jurusan', 'like', "%{$search}%");
                });
            });

        $totalAll = Siswa::count();
        $totalAktif = Siswa::where('status', 'Aktif')->count();
        $totalAlumni = Siswa::where('status', 'Alumni')->count();

        $siswas = $query->orderBy('nama')->paginate(10)->withQueryString();
        $kelasOptions = Siswa::query()->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $jurusanOptions = Siswa::query()->whereNotNull('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
        $angkatanOptions = Siswa::query()->whereNotNull('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');
        $status = $filters['status'] ?? null;

        return view('admin.siswa.index', compact('siswas', 'totalAll', 'totalAktif', 'totalAlumni', 'status', 'filters', 'kelasOptions', 'jurusanOptions', 'angkatanOptions'));
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
            ->get(['id', 'nama', 'nis', 'kelas', 'status']);

        return response()->json($results);
    }
}