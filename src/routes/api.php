<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    PostController
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

            Route::post('share', [PostController::class, 'share'])->name('share');
        });

        Route::prefix('search')->name('search.')->group(function () {
            Route::post('/', [PostController::class, 'searchPosts'])->name('posts');
            Route::post('pins', [PostController::class, 'searchPins'])->name('pins');
            Route::post('shares', [PostController::class, 'searchShares'])->name('shares');
            Route::post('own', [PostController::class, 'searchOwn'])->name('own');
        });
    });
});
