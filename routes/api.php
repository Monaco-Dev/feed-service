<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    PinController,
    PostController,
    ShareController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth.user')->group(function () {
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('{id}', [PostController::class, 'show'])->name('posts.show');
        Route::put('{id}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('{id}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('search', [PostController::class, 'search'])->name('posts.search');
    });

    Route::prefix('pins')->group(function () {
        Route::post('/', [PinController::class, 'store'])->name('pins.store');
        Route::get('/', [PinController::class, 'index'])->name('pins.index');
        Route::delete('{id}', [PinController::class, 'destroy'])->name('pins.destroy');
    });

    Route::prefix('shares')->group(function () {
        Route::post('/', [ShareController::class, 'store'])->name('shares.store');
        Route::get('/', [ShareController::class, 'index'])->name('shares.index');
        Route::delete('{id}', [ShareController::class, 'destroy'])->name('shares.destroy');
    });
});
