<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(\App\Http\Middleware\CheckUserBan::class)->group(function() {
    Route::get('/', [HomeController::class, 'show'])->name('home');

    Route::get('/auth/github/verify', function() {
        return \Laravel\Socialite\Facades\Socialite::driver('github')->redirect();
    });
    Route::get('/auth/github/callback', [\App\Http\Controllers\SocialiteController::class, 'github']);

    Route::get('/auth/google/verify', function() {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    });
    Route::get('/auth/google/callback', [\App\Http\Controllers\SocialiteController::class, 'google']);

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('/message', [\App\Http\Controllers\MessageController::class, 'create']);
        Route::delete('/message', [\App\Http\Controllers\MessageController::class, 'delete'])
            ->middleware(\App\Http\Middleware\CheckAdminPrivilege::class);
    });

    require __DIR__.'/auth.php';
});
