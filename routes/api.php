<?php

use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\PrestasiController;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(ApiKeyMiddleware::class)
    ->prefix('v1')
    ->group(function () {
        Route::get('/prestasi', [PrestasiController::class, 'index']);
        Route::get('/artikel', [ArtikelController::class, 'index']);
    });