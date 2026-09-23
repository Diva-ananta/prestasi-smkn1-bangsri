<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Models\ApiUsageLog;
use Illuminate\Http\Request;

class ApiClientController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_clients' => ApiClient::count(),
            'active_clients' => ApiClient::where('is_active', true)->count(),
            'total_requests' => ApiUsageLog::count(),
            'requests_today' => ApiUsageLog::whereDate('created_at', today())->count(),
        ];

        $clients = ApiClient::query()
            ->withCount('usageLogs')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.api-client.index', compact('clients', 'statistics'));
    }

    public function create()
    {
        return view('admin.api-client.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:api_clients,name'],
        ]);

        $apiClient = ApiClient::create([
            'name' => $validated['name'],
            'key' => 'sipres_' . \Illuminate\Support\Str::random(56),
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.api-client.show', $apiClient)
            ->with('api_key', $apiClient->key)
            ->with('success', 'API Client berhasil dibuat.');
    }

    public function show(ApiClient $apiClient)
    {
        $usageLogs = $apiClient->usageLogs()
            ->latest()
            ->paginate(20);

        return view('admin.api-client.show', compact(
            'apiClient',
            'usageLogs'
        ));
    }

    public function toggleStatus(ApiClient $apiClient)
    {
        $apiClient->update([
            'is_active' => !$apiClient->is_active,
        ]);

        return redirect()
            ->route('admin.api-client.index')
            ->with(
                'success',
                $apiClient->is_active
                    ? 'API Client berhasil diaktifkan.'
                    : 'API Client berhasil dinonaktifkan.'
            );
    }                                                   
}
