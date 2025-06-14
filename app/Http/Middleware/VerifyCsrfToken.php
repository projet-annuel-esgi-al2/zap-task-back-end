<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'google/calendar/webhook',
        'google/calendar/webhook/*',
        'webhooks/google/calendar',
        'webhooks/google/calendar/*',
    ];


}
