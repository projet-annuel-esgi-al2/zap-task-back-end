<?php

namespace App\Listeners\Workflow;

use App\Events\Workflow\WorkflowDeploymentTriggered;

class RefreshDeploymentId
{
    public function handle(WorkflowDeploymentTriggered $event): void
    {
        $event->workflow->refreshDeploymentId();
    }
}
