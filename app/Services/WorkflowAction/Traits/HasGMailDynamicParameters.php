<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\WorkflowAction\Traits;

use Illuminate\Support\Str;

trait HasGMailDynamicParameters
{
    use HasWorkflowAction;

    public function encodeBase64Url($value): string
    {
        return Str::of($value)
            ->toBase64()
            ->replace(['+', '/'], ['-', '_'])
            ->rtrim('=')
            ->value();
    }
}
