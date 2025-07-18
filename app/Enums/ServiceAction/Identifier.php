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

    case GoogleMailSend = 'google-mail-send';
    case GoogleCalendarEventUpdated = 'google-calendar-event-updated';
    case GoogleSheetsCreateSpreadsheet = 'google-sheets-create-spreadsheet';
    case GoogleDocsCreateEmptyDoc = 'google-docs-create-empty-doc';
    case GoogleSheetsAddRow = 'google-sheets-add-row';
    case GoogleCalendarCreateEvent = 'google-calendar-create-event';
    case GoogleDocsAddContent = 'google-docs-add-content';
    case GoogleCalendarDeleteEvent = 'google-calendar-delete-event';
    case GoogleTasksDeleteTask = 'google-tasks-delete-task';
    case GoogleTasksCreateTask = 'google-tasks-create-task';

    public static function isGoogleTrigger(Identifier $identifier): bool
    {
        return collect(self::valuesOnly([
            Identifier::GoogleCalendarEventCreated,
            Identifier::GoogleCalendarEventUpdated,
        ]))->contains($identifier->value);
    }


}
