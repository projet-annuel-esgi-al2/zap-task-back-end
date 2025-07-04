<?php

namespace App\Enums\Workflow;

use App\Enums\Traits\EnumTrait;

enum Status: string
{
    use EnumTrait;

    case Draft = 'draft';
    case Saved = 'saved';
    case Tested = 'tested';
    case Deployed = 'deployed';
}
