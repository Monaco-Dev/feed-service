<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
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
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::post('/', [PostController::class, 'store'])->name('store');

        Route::prefix('{post}')->group(function () {
            Route::get('/', [PostController::class, 'show'])->name('show');
            Route::put('/', [PostController::class, 'update'])->name('update');
            Route::delete('/', [PostController::class, 'destroy'])->name('destroy');
            Route::post('pin', [PostController::class, 'pin'])->name('pin');
            Route::post('unpin', [PostController::class, 'unpin'])->name('unpin');
        });

        Route::prefix('search')->name('search.')->group(function () {
            Route::post('/', [PostController::class, 'searchPosts'])->name('posts');
            Route::post('pins', [PostController::class, 'searchPins'])->name('pins');
        });
    });

    Route::prefix('shares')->group(function () {
        Route::post('/', [ShareController::class, 'store'])->name('shares.store');
        Route::get('/', [ShareController::class, 'index'])->name('shares.index');
        Route::delete('{id}', [ShareController::class, 'destroy'])->name('shares.destroy');
    });
});
