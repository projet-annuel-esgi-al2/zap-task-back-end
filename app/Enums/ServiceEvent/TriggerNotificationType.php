<?php

namespace App\Enums\ServiceEvent;

use App\Enums\Traits\EnumTrait;

enum TriggerNotificationType: string
{
    use EnumTrait;

    case Polling = 'polling';
    case Webhook = 'webhook';
}
