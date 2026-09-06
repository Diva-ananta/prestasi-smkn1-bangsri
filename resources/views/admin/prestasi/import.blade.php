@extends('layouts.admin')

@section('title', 'Import Excel')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.prestasi.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Import Data Prestasi</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.prestasi.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Excel</label>
                <input type="file" name="file" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" accept=".xlsx,.xls,.csv" required>
                @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center justify-end gap-3 mt-6">
                <a href="{{ route('admin.prestasi.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-sm font-medium transition">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition">Import</button>
            </div>
        </form>

        <div class="mt-6 text-sm text-gray-500 border-t border-gray-100 pt-6">
            <p><b>Format Excel yang diharapkan:</b></p>
            <ul class="list-disc list-inside space-y-1 mt-2">
                <li><span class="font-medium">nama_lomba</span>, <span class="font-medium">hasil</span>, <span class="font-medium">jenis_peserta</span>, dan <span class="font-medium">siswa</span> (wajib)</li>
                <li><span class="font-medium">penyelenggara</span>, <span class="font-medium">tingkat</span>, <span class="font-medium">kategori</span>, <span class="font-medium">lokasi</span>, <span class="font-medium">tanggal_mulai</span> (opsional)</li>
                <li><span class="font-medium">siswa</span> berisi nama siswa atau NIS; pisahkan beberapa peserta dengan koma</li>
                <li><span class="font-medium">nama_tim</span>, <span class="font-medium">status</span>, dan <span class="font-medium">keterangan</span> (opsional)</li>
                <li>Kolom <span class="font-medium">foto</span> dapat berisi URL gambar publik. URL dari hasil export prestasi dapat langsung diimport kembali.</li>
            </ul>
        </div>
    </div>
</div>
@endsection