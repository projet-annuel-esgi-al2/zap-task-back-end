<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Actions\WorkflowAction;

use App\Enums\WorkflowActionHistory\ExecutionStatus;
use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Models\WorkflowActionHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateWorkflowActionHistory implements ShouldQueue, ShouldQueueAfterCommit
{
    use AsAction;
    use Queueable;

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

    public function asListener(WorkflowActionExecuted $event): void
    {
        $this->handle($event);
    }
}
