<?php

namespace App\Http\Integrations\ListCalendarsRequest\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListCalendarsRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/users/me/calendarList';
    }
}
