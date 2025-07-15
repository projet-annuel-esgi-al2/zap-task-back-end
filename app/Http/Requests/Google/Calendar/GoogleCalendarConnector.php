<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Requests\Google\Calendar;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class GoogleCalendarConnector extends Connector
{
    use AcceptsJson;

    public function __construct(protected string $oauthToken) {}

    public function resolveBaseUrl(): string
    {
        return config('services.google.calendar.base_url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->oauthToken,
        ];
    }
}
