<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    PostController,
    TagController
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
        Route::get('{uuid}', [PostController::class, 'show'])->middleware('post')->name('show');

        Route::prefix('{post}')->group(function () {
            Route::post('update', [PostController::class, 'update'])->name('update');
            Route::delete('/', [PostController::class, 'destroy'])->name('destroy');

            Route::post('pin', [PostController::class, 'pin'])->name('pin');
            Route::post('unpin', [PostController::class, 'unpin'])->name('unpin');

            Route::post('share', [PostController::class, 'share'])->name('share');
        });
        Route::post('{trashed_post}/restore', [PostController::class, 'restore'])->name('restore');

        Route::prefix('search')->name('search.')->group(function () {
            Route::post('/', [PostController::class, 'searchPosts'])->name('posts');
            Route::post('pins', [PostController::class, 'searchPins'])->name('pins');
            Route::post('shares', [PostController::class, 'searchShares'])->name('shares');
            Route::post('wall/{user}', [PostController::class, 'searchWall'])->name('wall');
            Route::post('{post}/matches', [PostController::class, 'searchMatches'])->name('matches');
            Route::post('archives', [PostController::class, 'searchArchives'])->name('archives');
        });
    });

    Route::prefix('tags')->name('tags.')->group(function () {
        Route::post('search', [TagController::class, 'search'])->name('search');
    });
});
