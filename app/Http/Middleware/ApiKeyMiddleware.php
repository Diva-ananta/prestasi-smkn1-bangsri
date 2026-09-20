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

        if (!$expectedKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key SiPres belum dikonfigurasi.',
            ], 500);
        }

        if (!$providedKey || !hash_equals($expectedKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API key tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}