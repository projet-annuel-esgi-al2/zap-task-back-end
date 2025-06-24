<?php

namespace App\Enums\ServiceAction;

use App\Enums\Traits\EnumTrait;

enum Identifier: string
{
    use EnumTrait;

    case GoogleCalendarEventCreated = 'google-calendar-event-created';
}
