<?php

namespace App\Enums\ServiceAction;

use App\Enums\Traits\EnumTrait;

enum TriggerNotificationType: string
{
    use EnumTrait;

    case Polling = 'polling';
    case Webhook = 'webhook';
}
