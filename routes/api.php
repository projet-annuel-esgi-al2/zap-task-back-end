<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::middleware(['auth'])->group(function () {
    Route::get('/google/redirect',  [GoogleController::class, 'redirectToGoogle']);
    Route::get('/google/callback',  [GoogleController::class, 'handleGoogleCallback']);
    Route::get('/google/events',    [GoogleController::class, 'showGoogleCalendarEvents']);
});
