<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\ServiceOAuthController;
use App\Http\Middleware\VerifyPersonalAccessToken;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::prefix('/register')->group(function () {
    Route::post('/', RegisterUserController::class);
});

Route::prefix('/me')->group(function () {
    Route::post('/', AuthenticateUserController::class);
});

Route::middleware([VerifyPersonalAccessToken::class])->group(function () {

    Route::prefix('subscriptions/{serviceIdentifier}')->group(function () {
        Route::get('/', [ServiceOAuthController::class, 'get']);
    });

    Route::get('{serviceIdentifier}/redirect', [ServiceOAuthController::class, 'redirect']);
    Route::get('{serviceIdentifier}/callback', [ServiceOAuthController::class, 'callback']);

    Route::get('/users/{user}', function (User $user) {
        return UserResource::make($user);
    });
});
