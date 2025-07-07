<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceOAuthController;
use App\Http\Controllers\WorkflowActionController;
use App\Http\Controllers\WorkflowController;
use App\Http\Middleware\VerifyPersonalAccessToken;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
Route::post('/me', AuthenticateUserController::class);

Route::get('/oauth/callback', [ServiceOAuthController::class, 'callback'])
    ->name('service-oauth-callback');

Route::middleware([VerifyPersonalAccessToken::class])->group(function () {

    Route::get('/subscriptions/{serviceIdentifier}', [ServiceOAuthController::class, 'get']);

    Route::prefix('{serviceIdentifier}/')->group(function () {
        Route::get('redirect', [ServiceOAuthController::class, 'redirect'])
            ->name('service-oauth-redirect');

        Route::get('actions', [ServiceController::class, 'get']);
    });

    Route::get('services', [ServiceController::class, 'index']);

    Route::prefix('/workflows')->group(function () {
        Route::get('/', [WorkflowController::class, 'index']);
        Route::get('/{workflow}', [WorkflowController::class, 'show']);
        Route::put('/{workflow?}', [WorkflowController::class, 'createOrUpdate']);
        Route::delete('/{workflow}', [WorkflowController::class, 'destroy']);
    });

    Route::prefix('/actions')->group(function () {
        Route::post('/execute/{action}', [WorkflowActionController::class, 'execute']);
    });
});

Route::webhooks('/workflows/trigger', 'trigger-workflow-webhook');
