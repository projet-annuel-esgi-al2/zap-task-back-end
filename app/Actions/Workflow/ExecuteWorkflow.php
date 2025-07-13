<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Actions\Workflow;

use App\Events\WorkflowActionTriggered;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Queue\SerializesModels;
use Lorisleiva\Actions\Concerns\AsAction;

class ExecuteWorkflow implements ShouldQueue, ShouldQueueAfterCommit
{
    use AsAction;
    use Queueable;
    use SerializesModels;

    public function handle(Workflow $workflow): void
    {
        $executableActions = $workflow->executableActions()
            ->with([
                'workflow',
                'serviceAction',
            ])
            ->orderBy('execution_order');

        $executableActions->each(fn (WorkflowAction $action) => WorkflowActionTriggered::dispatch($action));
    }
}
