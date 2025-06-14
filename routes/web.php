<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GoogleOAuthController;
use App\Http\Controllers\Webhooks\GoogleCalendarWebhookController;



Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});


// OAuth Google
Route::get('/google/redirect', [GoogleOAuthController::class, 'redirect']);
Route::get('/google/callback', [GoogleOAuthController::class, 'callback']);

// Webhook Google Calendar
//Route::post('/webhooks/google/calendar', [GoogleCalendarWebhookController::class, '__invoke']);
Route::post('/google/calendar/webhook', GoogleCalendarWebhookController::class)
    ->withoutMiddleware([\Illuminate\Session\Middleware\StartSession::class, \Illuminate\View\Middleware\ShareErrorsFromSession::class])
    ->name('google.calendar.webhook');



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
