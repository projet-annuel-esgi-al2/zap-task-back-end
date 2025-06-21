<?php

namespace App\Models\Enums\ServiceEvent;

use App\Models\Enums\Traits\EnumTrait;

enum Type: string
{
    use EnumTrait;

    case Trigger = 'trigger';
    case Action = 'action';
}
