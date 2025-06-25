<?php

namespace App\Listeners\WorkflowAction;

use App\Enums\WorkflowActionHistory\ExecutionStatus;
use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Models\WorkflowActionHistory;

class CreateWorkflowActionHistory
{
    public function handle(WorkflowActionExecuted $event): void
    {
        $workflowAction = $event->workflowAction;

        WorkflowActionHistory::create([
            'execution_status' => $event->success ? ExecutionStatus::Success : ExecutionStatus::Error,
            'execution_order' => $workflowAction->execution_order,
            'exception' => $event->exception,
            'body_parameters' => $workflowAction->body_parameters,
            'url_parameters' => $workflowAction->url_parameters,
            'query_parameters' => $workflowAction->query_parameters,
            'executed_at' => $event->executedAt,
        ]);
    }
}
