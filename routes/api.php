<?php

use App\Http\Controllers\Api\PrestasiController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Middleware\ApiClientMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'status' => 'ok',
        'service' => 'SiPres API',
        'version' => 'v1',
    ]);
});

Route::middleware([
    ApiClientMiddleware::class,
    'throttle:60,1',
])
    ->prefix('v1')
    ->group(function () {
        Route::get('/prestasi', [PrestasiController::class, 'index']);

        Route::get('/artikel', [ArtikelController::class, 'index']);
        Route::get('/artikel/{slug}', [ArtikelController::class, 'show']);
    });