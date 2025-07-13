<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Requests\Google\Calendar\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class WatchCalendar extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $calendarId,
        protected array $channel
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/calendars/{$this->calendarId}/events/watch";
    }

    public function defaultBody(): array
    {
        return $this->channel;
    }
}
