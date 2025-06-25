<?php

use App\Http\Webhooks\GoogleCalendarWebhookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

// Webhook Google Calendar
Route::post('/webhooks/google/calendar', [GoogleCalendarWebhookController::class, '__invoke']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
