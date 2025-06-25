<?php

namespace App\Enums\Workflow;

use App\Enums\Traits\EnumTrait;

enum Status: string
{
    use EnumTrait;

    case Draft = 'draft';
    case Tested = 'tested';
    case Deployed = 'deployed';
}
