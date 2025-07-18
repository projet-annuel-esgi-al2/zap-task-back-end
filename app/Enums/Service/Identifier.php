<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Enums\Service;

use App\Enums\Traits\EnumTrait;

enum Identifier: string
{
    use EnumTrait;

    case GoogleCalendar = 'google-calendar';
    case GoogleMail = 'google-mail';

    case GoogleSheets = 'google-sheets';
    case GoogleDocs = 'google-docs';
    case GoogleTasks = 'google-tasks';

}
