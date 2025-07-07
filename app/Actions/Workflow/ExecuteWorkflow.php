<?php

namespace App\Actions\Workflow;

use App\Actions\WorkflowAction\ExecuteWorkflowAction;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Lorisleiva\Actions\Concerns\AsAction;

class ExecuteWorkflow implements ShouldQueue, ShouldQueueAfterCommit
{
    use AsAction;
    use Queueable;

    public function handle(Workflow $workflow): void
    {
        $executableActions = $workflow->executableActions()
            ->orderBy('execution_order');

        $executableActions->each(fn (WorkflowAction $action) => ExecuteWorkflowAction::run($action));
    }
}
