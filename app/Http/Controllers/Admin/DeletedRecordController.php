<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeletedRecord;
use App\Models\DetailPrestasi;
use App\Models\Prestasi;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class DeletedRecordController extends Controller
{
    public function index()
    {
        $records = DeletedRecord::with('deleter')->latest('deleted_at')->paginate(20);
        return view('admin.deleted-records.index', compact('records'));
    }

    public function restore(?DeletedRecord $deletedRecord)
    {
        if (! $deletedRecord) return back()->with('error', 'Riwayat data sudah tidak tersedia atau sudah dipulihkan.');

        DB::transaction(function () use ($deletedRecord) {
            $snapshot = $deletedRecord->snapshot;
            $model = $deletedRecord->model_type;
            $data = $snapshot['data'];
            $restored = $model::find($deletedRecord->model_id);
            if (! $restored) {
                $restored = new $model;
                $restored->setRawAttributes($data);
                $restored->exists = false;
                $restored->saveQuietly();
            }

            foreach ($snapshot['details'] ?? [] as $detail) {
                DetailPrestasi::withoutEvents(fn () => DetailPrestasi::firstOrCreate(
                    ['prestasi_id' => $detail['prestasi_id'], 'siswa_id' => $detail['siswa_id']],
                    ['peran' => $detail['peran'] ?? 'Anggota']
                ));
            }
            $deletedRecord->delete();
        });

        return back()->with('success', 'Data berhasil dipulihkan.');
    }

    public function destroy(?DeletedRecord $deletedRecord)
    {
        if (! $deletedRecord) return back()->with('error', 'Riwayat data sudah tidak tersedia.');

        $deletedRecord->delete();
        return back()->with('success', 'Riwayat dihapus permanen.');
    }
}
