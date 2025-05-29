<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::prefix('/register')->group(function () {
    Route::post('/', RegisterUserController::class);
});

Route::prefix('/me')->group(function () {
    Route::post('/', AuthenticateUserController::class);
});

Route::get('/user/{user}', function (User $user) {
    return UserResource::make($user);
});
