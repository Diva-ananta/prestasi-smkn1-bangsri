<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\PrestasiController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\ImportPrestasiController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\OAuthController;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/siswa', [PublicController::class, 'siswaSearch'])->name('public.siswa.search');
Route::get('/prestasi', [PublicController::class, 'prestasiIndex'])->name('public.prestasi.index');
Route::get('/prestasi/{prestasi}', [PublicController::class, 'prestasiShow'])->name('public.prestasi.show');
Route::get('/siswaberprestasi/{nama}', [PublicController::class, 'siswaShowByName'])->name('public.siswa.profile');
Route::get('/siswa/{token}', [PublicController::class, 'siswaShow'])->name('public.siswa.show');
Route::get('/artikel', [PublicController::class, 'artikelIndex'])->name('public.artikel.index');
Route::get('/galeri', [PublicController::class, 'galeriIndex'])->name('public.galeri.index');
Route::get('/artikel/{artikel}', [PublicController::class, 'artikelShow'])->name('public.artikel.show');
Route::get('/tentang', [PublicController::class, 'tentang'])->name('public.tentang');

/*
|--------------------------------------------------------------------------
| Dashboard & Profile (hanya untuk user yang login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN - Hanya untuk user dengan is_admin = 1
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('prestasi/import', [ImportPrestasiController::class, 'index'])->name('prestasi.import');
        Route::post('prestasi/import', [ImportPrestasiController::class, 'store'])->name('prestasi.import.store');
        Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
        Route::get('prestasi/export', [PrestasiController::class, 'export'])->name('prestasi.export');
        Route::patch('prestasi/{prestasi}/review', [PrestasiController::class, 'review'])->name('prestasi.review');
        Route::get('deleted-records', [\App\Http\Controllers\Admin\DeletedRecordController::class, 'index'])->name('deleted-records.index');
        Route::post('deleted-records/{deletedRecord?}/restore', [\App\Http\Controllers\Admin\DeletedRecordController::class, 'restore'])->name('deleted-records.restore');
        Route::delete('deleted-records/{deletedRecord?}', [\App\Http\Controllers\Admin\DeletedRecordController::class, 'destroy'])->name('deleted-records.destroy');

        // Endpoint AJAX untuk pencarian siswa (dipakai oleh form prestasi)
        Route::get('siswa/search', [SiswaController::class, 'search'])->name('siswa.search');
        // CRUD Siswa
        Route::resource('siswa', SiswaController::class);

        // CRUD Prestasi
        Route::resource('prestasi', PrestasiController::class)->except(['show']);

        // CRUD Artikel
        Route::resource('artikel', ArtikelController::class);

        // CRUD Galeri
        Route::resource('galeri', GaleriController::class)->only(['index', 'store', 'destroy']);

        // Integrasi SiPintu Gateway
        Route::get('sipintu', [\App\Http\Controllers\Admin\SiPintuController::class, 'index'])->name('sipintu.index');
        Route::post('sipintu/ping', [\App\Http\Controllers\Admin\SiPintuController::class, 'ping'])->name('sipintu.ping');
        Route::post('sipintu/validate-client', [\App\Http\Controllers\Admin\SiPintuController::class, 'validateClient'])->name('sipintu.validate-client');
        Route::post('sipintu/sync-students', [\App\Http\Controllers\Admin\SiPintuController::class, 'syncStudents'])->name('sipintu.sync-students');
        Route::get('sipintu/search-students', [\App\Http\Controllers\Admin\SiPintuController::class, 'searchStudents'])->name('sipintu.search-students');
        Route::get('sipintu/search-teachers', [\App\Http\Controllers\Admin\SiPintuController::class, 'searchTeachers'])->name('sipintu.search-teachers');

    });

// Endpoint penerima redirect SSO otomatis dari SiPintu Gateway
Route::get('/oauth/callback', [OAuthController::class, 'callback'])->name('oauth.callback');

// Alias dashboard untuk akses SSO
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';