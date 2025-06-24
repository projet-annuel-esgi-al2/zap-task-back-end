<?php

namespace App\Enums\ServiceAction;

use App\Enums\Traits\EnumTrait;

enum Type: string
{
    use EnumTrait;

    case Trigger = 'trigger';
    case Action = 'action';
}
