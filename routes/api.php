<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

use App\Actions\Workflow\ExecuteWorkflow;
use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceOAuthController;
use App\Http\Controllers\WorkflowActionController;
use App\Http\Controllers\WorkflowActionHistoryController;
use App\Http\Controllers\WorkflowController;
use App\Http\Middleware\VerifyPersonalAccessToken;
use App\Models\Workflow;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
Route::post('/me', AuthenticateUserController::class);

Route::get('/oauth/callback', [ServiceOAuthController::class, 'callback'])
    ->name('service-oauth-callback');

Route::middleware([VerifyPersonalAccessToken::class])->group(function () {

    Route::get('/subscriptions', [ServiceController::class, 'subscriptions']);
    Route::get('/subscriptions/{serviceIdentifier}', [ServiceOAuthController::class, 'get']);
    Route::delete('/subscriptions/{serviceIdentifier}', [ServiceOAuthController::class, 'unsubscribe']);

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
        Route::post('/execute/{workflow}', function (Workflow $workflow) {
            ExecuteWorkflow::dispatch($workflow);
        });
        Route::post('/deploy/{workflow}', [WorkflowController::class, 'deploy']);
        Route::post('/undeploy/{workflow}', [WorkflowController::class, 'undeploy']);
        Route::delete('/{workflow}', [WorkflowController::class, 'destroy']);
    });

    Route::prefix('/actions')->group(function () {
        Route::post('/execute/{action}', [WorkflowActionController::class, 'execute']);
        Route::delete('/{action}', [WorkflowActionController::class, 'delete']);
    });
    Route::prefix('/logs')->group(function () {
        Route::get('/{workflow}', [WorkflowActionHistoryController::class, 'show']);
    });
});

Route::webhooks('/workflows/trigger', 'trigger-workflow-webhook');
