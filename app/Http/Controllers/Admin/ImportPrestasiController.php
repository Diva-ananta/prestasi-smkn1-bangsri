<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PrestasiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ImportPrestasiController extends Controller
{
    public function index()
    {
        return view('admin.prestasi.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            DB::transaction(fn () => Excel::import(new PrestasiImport, $request->file('file')));
            return redirect()->route('admin.prestasi.index')
                ->with('success', 'Data prestasi berhasil diimport.');
        } catch (\Exception $e) {
            Log::error('Import prestasi gagal.', [
                'exception' => $e,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Import gagal diproses. Periksa format file dan coba lagi.');
        }
    }
}