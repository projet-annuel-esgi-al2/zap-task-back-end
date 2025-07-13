<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Requests\Google\Calendar\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCalendars extends Request
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
