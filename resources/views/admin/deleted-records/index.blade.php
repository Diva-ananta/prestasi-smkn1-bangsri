@extends('layouts.admin')
@section('title', 'Riwayat Data Dihapus')
@section('content')
<div class="page-shell">
    <div class="page-header"><h1 class="text-2xl font-bold">Riwayat Data Dihapus</h1><p class="mt-2 text-sm text-slate-500">Pulihkan atau hapus permanen data yang dihapus oleh admin.</p></div>
    <div class="section-card overflow-x-auto"><table class="admin-table w-full"><thead><tr><th>Data</th><th>Dihapus oleh</th><th>Waktu</th><th>Aksi</th></tr></thead><tbody>
    @forelse($records as $record)<tr><td>{{ class_basename($record->model_type) }} #{{ $record->model_id }}</td><td>{{ $record->deleter?->name ?? 'Tidak diketahui' }}</td><td>{{ $record->deleted_at?->format('d-m-Y H:i') }}</td><td class="flex gap-2"><form method="POST" action="{{ route('admin.deleted-records.restore', $record) }}">@csrf<button class="admin-btn-secondary">Pulihkan</button></form><form method="POST" action="{{ route('admin.deleted-records.destroy', $record) }}" onsubmit="return confirm('Hapus permanen?')">@csrf @method('DELETE')<button class="admin-btn-secondary text-red-600">Hapus permanen</button></form></td></tr>
    @empty <tr><td colspan="4" class="py-10 text-center text-slate-400">Belum ada riwayat.</td></tr>@endforelse
    </tbody></table></div><div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
