<?php

use App\Enums\Service\Identifier;
use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\ServiceOAuthController;
use App\Http\Middleware\VerifyPersonalAccessToken;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Service;
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

    Route::prefix('{serviceIdentifier}/')->group(function () {
        /** ServiceOAuth routes */
        Route::get('redirect', [ServiceOAuthController::class, 'redirect'])
            ->name('service-oauth-redirect');
        Route::get('callback', [ServiceOAuthController::class, 'callback']);
        /** end */
        Route::get('actions', function (Identifier $serviceIdentifier) {
            return Service::with('actions')
                ->where('identifier', $serviceIdentifier)
                ->first()
                ->toResource(ServiceResource::class);
        });
    });

    Route::get('services', function () {
        return Service::all()->toResourceCollection(ServiceResource::class);
    });

    Route::get('/users/{user}', function (User $user) {
        return UserResource::make($user);
    });
});
