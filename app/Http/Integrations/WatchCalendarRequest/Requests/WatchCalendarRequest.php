<?php

namespace App\Http\Integrations\WatchCalendarRequest\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class WatchCalendarRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $calendarId,
        protected array $channel
    ) {}

    public function resolveEndpoint(): string
    {
        return "/calendars/{$this->calendarId}/events/watch";
    }

    public function defaultBody(): array
    {
        return $this->channel;
    }
}
