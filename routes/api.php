<?php

use App\Http\Controllers\Api\PrestasiController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'status' => 'ok',
        'service' => 'SiPres API',
        'version' => 'v1',
    ]);
});

Route::middleware([
    ApiKeyMiddleware::class,
    'throttle:60,1',
])
    ->prefix('v1')
    ->group(function () {
        Route::get('/prestasi', [PrestasiController::class, 'index']);
        
        Route::get('/artikel', [ArtikelController::class, 'index']);
        Route::get('/artikel/{slug}', [ArtikelController::class, 'show']);
    });