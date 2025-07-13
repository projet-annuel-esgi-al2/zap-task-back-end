<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Enums\ServiceAction;

use App\Enums\Traits\EnumTrait;

enum TriggerNotificationType: string
{
    use EnumTrait;

    case Polling = 'polling';
    case Webhook = 'webhook';
}
