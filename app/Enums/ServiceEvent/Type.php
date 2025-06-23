<?php

namespace App\Enums\ServiceEvent;

use App\Enums\Traits\EnumTrait;

enum Type: string
{
    use EnumTrait;

    case Trigger = 'trigger';
    case Action = 'action';
}
