<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GoogleController;


Route::get('/', function () {
    return response()->json(['message' => 'API Zap Task']);
});

/** 

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});



//GOOGLE

Route::middleware(['auth'])->group(function () {
    Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    
    // Route pour récupérer les événements Google Calendar de la semaine (en JSON)
    Route::get('/google/events', [GoogleController::class, 'showGoogleCalendarEvents'])->name('google.events');
});

*/







require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
