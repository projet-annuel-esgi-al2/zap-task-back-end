<?php

namespace App\Listeners\Workflow;

use App\Enums\ServiceAction\Identifier;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Events\WorkflowAction\WorkflowActionTriggered;

class DeployWorkflow
{
    public function handle(WorkflowDeploymentTriggered $event): void
    {
        $workflow = $event->workflow;
        $trigger = $workflow->trigger;

        if ($trigger->serviceAction->identifier === Identifier::GoogleCalendarEventCreated) {
            // handle logic of saving the first sync token here
        }

        WorkflowActionTriggered::dispatch($trigger);

        $workflow->setAsDeployed();
    }
}
