<?php

namespace App\Actions\Workflow;

use App\Enums\Workflow\Status as WorkflowStatus;
use App\Enums\WorkflowAction\Status as WorkflowActionStatus;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Lorisleiva\Actions\Concerns\AsAction;

class SetWorkflowAsTestedIfPossible
{
    use AsAction;

    public function handle(Workflow $workflow)
    {
        if ($workflow->status === WorkflowStatus::Tested) {
            return;
        }

        $allActionsTested = $workflow->actions
            ->every(fn (WorkflowAction $action) => $action->status === WorkflowActionStatus::Tested);

        if ($allActionsTested) {
            $workflow->setAsTested();
        }
    }

    public function asListener(WorkflowDeploymentTriggered $event): void
    {
        $this->handle($event->workflow);
    }
}
