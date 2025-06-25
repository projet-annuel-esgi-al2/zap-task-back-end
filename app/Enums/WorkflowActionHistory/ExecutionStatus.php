<?php

namespace App\Enums\WorkflowActionHistory;

use App\Enums\Traits\EnumTrait;

enum ExecutionStatus: string
{
    use EnumTrait;

    case Success = 'success';
    case Error = 'error';
}
