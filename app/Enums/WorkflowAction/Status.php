<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Enums\WorkflowAction;

use App\Enums\Traits\EnumTrait;

enum Status: string
{
    use EnumTrait;

    case Draft = 'draft';
    case Tested = 'tested';
    case Error = 'error';
}
