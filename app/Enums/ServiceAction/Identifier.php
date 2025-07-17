<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Enums\ServiceAction;

use App\Enums\Traits\EnumTrait;

enum Identifier: string
{
    use EnumTrait;

    case GoogleCalendarEventCreated = 'google-calendar-event-created';
    case GoogleCalendarEventUpdated = 'google-calendar-event-updated';
    case GoogleCalendarCreateEvent = 'google-calendar-create-event';
    case GoogleMailSend = 'google-mail-send';

    public static function isGoogleTrigger(Identifier $identifier): bool
    {
        return collect(self::valuesOnly([
            Identifier::GoogleCalendarEventCreated,
            Identifier::GoogleCalendarEventUpdated,
        ]))->contains($identifier->value);
    }
}
