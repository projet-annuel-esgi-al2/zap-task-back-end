<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\WorkflowAction\Traits;

use App\Models\WorkflowAction;

trait HasWorkflowAction
{
    abstract public function workflowAction(): WorkflowAction;
}
