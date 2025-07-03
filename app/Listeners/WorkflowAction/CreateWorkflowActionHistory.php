<?php

namespace App\Listeners\WorkflowAction;

use App\Enums\WorkflowActionHistory\ExecutionStatus;
use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Models\WorkflowActionHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class CreateWorkflowActionHistory implements ShouldQueue, ShouldQueueAfterCommit
{
    public function handle(WorkflowActionExecuted $event): void
    {
        $workflowAction = $event->workflowAction;

        WorkflowActionHistory::create([
            'workflow_action_id' => $workflowAction->id,
            'response_http_code' => $event->httpCode,
            'execution_status' => $event->success ? ExecutionStatus::Success : ExecutionStatus::Error,
            'execution_order' => $workflowAction->execution_order,
            'exception' => $event->exception,
            'parameters' => $workflowAction->getParametersForApi(),
            'executed_at' => $event->executedAt,
        ]);
    }
}
