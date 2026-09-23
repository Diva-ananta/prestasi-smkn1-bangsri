<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\ApiUsageLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiClientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

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

        $response = $next($request);

        $responseTime = (int) round(
            (microtime(true) - $startTime) * 1000
        );

        ApiUsageLog::create([
            'api_client_id' => $client->id,
            'method' => $request->method(),
            'endpoint' => '/' . ltrim($request->path(), '/'),
            'status_code' => $response->getStatusCode(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'response_time_ms' => $responseTime,
        ]);

        return $response;
    }
}