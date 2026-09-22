<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiClientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $providedKey = $request->header('X-API-KEY');

        if (!$providedKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key wajib dikirim.',
                'error_code' => 'API_KEY_REQUIRED',
            ], 401);
        }

        $client = ApiClient::where('key', $providedKey)
            ->where('is_active', true)
            ->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'API key tidak valid atau tidak aktif.',
                'error_code' => 'INVALID_API_KEY',
            ], 401);
        }

        $client->update([
            'last_used_at' => now(),
        ]);

        $request->attributes->set('api_client', $client);

        return $next($request);
    }
}