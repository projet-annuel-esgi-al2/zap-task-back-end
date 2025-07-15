<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\ParameterResolver\WorkflowAction\Traits;

use App\Models\WorkflowAction;

trait HasWorkflowAction
{
    abstract public function workflowAction(): WorkflowAction;
}
