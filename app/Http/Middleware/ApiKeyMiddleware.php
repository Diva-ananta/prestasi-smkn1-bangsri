<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = config('services.sipres_api.key');
        $providedKey = $request->header('X-API-KEY');

        // API key belum dikonfigurasi di server
        if (!$expectedKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key SiPres belum dikonfigurasi.',
                'error_code' => 'API_KEY_NOT_CONFIGURED',
            ], 500);
        }

        // API key tidak dikirim
        if (!$providedKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key wajib dikirim.',
                'error_code' => 'API_KEY_REQUIRED',
            ], 401);
        }

        // API key salah
        if (!hash_equals($expectedKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API key tidak valid.',
                'error_code' => 'INVALID_API_KEY',
            ], 401);
        }

        return $next($request);
    }
}