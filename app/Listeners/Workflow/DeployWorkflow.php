<?php

namespace App\Listeners\Workflow;

use App\Enums\Workflow\Status;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Events\WorkflowAction\WorkflowActionTriggered;

class DeployWorkflow
{
    public function handle(WorkflowDeploymentTriggered $event): void
    {
        $workflow = $event->workflow;

        if ($workflow->status !== Status::Tested) {
            return;
        }

        $trigger = $workflow->trigger;

        WorkflowActionTriggered::dispatch($trigger);

        $workflow->setAsDeployed();
    }
}
