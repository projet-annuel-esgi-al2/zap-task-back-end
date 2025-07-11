<?php

namespace App\Enums\Service;

use App\Enums\Traits\EnumTrait;

enum Identifier: string
{
    use EnumTrait;

    case GoogleCalendar = 'google-calendar';
    case GoogleMail = 'google-mail';
}
