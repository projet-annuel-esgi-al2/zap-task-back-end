<?php

namespace App\Enums\ServiceAction;

use App\Enums\Traits\EnumTrait;

enum Identifier: string
{
    use EnumTrait;

    case GoogleCalendarEventCreated = 'google-calendar-event-created';
    case GoogleCalendarEventUpdated = 'google-calendar-event-updated';
    case GoogleCalendarCreateEvent = 'google-calendar-create-event';
    case GoogleMailSend = 'google-mail-send';
}
