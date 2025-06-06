<?php

namespace App\Services;

use App\Models\Workflow;
use App\Models\Execution;

class WorkflowExecutor
{
    public function run(Workflow $workflow): ?Execution
    {
        // Créer execution
        // Boucler sur les étapes
        // Appeler trigger/action
        // Log via ExecutionLog
        return null;
    }
}
