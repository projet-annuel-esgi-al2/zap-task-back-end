<?php

use App\Enums\Service\Identifier;
use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\ServiceOAuthController;
use App\Http\Controllers\WorkflowController;
use App\Http\Middleware\VerifyPersonalAccessToken;
use App\Http\Resources\Api\ServiceResource;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
Route::post('/me', AuthenticateUserController::class);

Route::get('/{serviceIdentifier}/callback', [ServiceOAuthController::class, 'callback'])
    ->name('service-oauth-callback');

Route::middleware([VerifyPersonalAccessToken::class])->group(function () {

    Route::get('/subscriptions/{serviceIdentifier}', [ServiceOAuthController::class, 'get']);

    Route::prefix('{serviceIdentifier}/')->group(function () {
        /** ServiceOAuth routes */
        Route::get('redirect', [ServiceOAuthController::class, 'redirect'])
            ->name('service-oauth-redirect');

        /**
         * @group Services and Actions
         * Fetch actions for a specified Service
         * */
        Route::get('actions', function (Identifier $serviceIdentifier) {
            return Service::with('actions')
                ->where('identifier', $serviceIdentifier)
                ->first()
                ->toResource(ServiceResource::class);
        });
    });

    /**
     * @group Services and Actions
     *
     * Fetch all available services
     */
    Route::get('services', function () {
        return Service::all()->toResourceCollection(ServiceResource::class);
    });

    Route::prefix('/workflows')->group(function () {
        Route::get('/', [WorkflowController::class, 'index']);
        Route::get('/{workflow}', [WorkflowController::class, 'show']);
        Route::put('{workflow?}', [WorkflowController::class, 'createOrUpdate']);
        Route::delete('/{workflow}', [WorkflowController::class, 'destroy']);
    });
});
