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
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('{id}', [PostController::class, 'show'])->name('posts.show');
        Route::put('{id}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('{id}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('search', [PostController::class, 'search'])->name('posts.search');
    });
});
