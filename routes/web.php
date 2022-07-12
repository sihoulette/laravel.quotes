<?php

use App\Http\Controllers;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localizationRedirect']
], function () {
    # Home route
    Route::get('/', [Controllers\HomeController::class, 'index']);

    # Auth routes
    Route::middleware('guest')->group(function () {
        Route::get('register', [Auth\RegisteredUserController::class, 'create'])
            ->name('register');
        Route::post('register', [Auth\RegisteredUserController::class, 'store']);

        Route::get('login', [Auth\AuthenticatedSessionController::class, 'create'])
            ->name('login');
        Route::post('login', [Auth\AuthenticatedSessionController::class, 'store']);
    });
    Route::middleware('auth')->group(function () {
        Route::post('logout', [Auth\AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });

    # Post routes
    Route::middleware('auth')->group(function () {
        Route::resource('post', Controllers\PostController::class);
    });

    # Post share routes
    Route::group(['prefix' => 'share', 'as'=>'share.'], function () {
        Route::post('telegram', [Controllers\PostShareController::class, 'telegram'])
            ->name('telegram');
        Route::post('viber', [Controllers\PostShareController::class, 'viber'])
            ->name('viber');
        Route::post('email', [Controllers\PostShareController::class, 'email'])
            ->name('e-mail');
    });
});
