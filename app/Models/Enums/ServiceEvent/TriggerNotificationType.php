<?php

namespace App\Models\Enums\ServiceEvent;

use App\Models\Enums\Traits\EnumTrait;

enum TriggerNotificationType: string
{
    use EnumTrait;

    case Polling = 'polling';
    case Webhook = 'webhook';
}
