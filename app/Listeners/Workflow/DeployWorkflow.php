<?php

namespace App\Listeners\Workflow;

use App\Enums\ServiceAction\Identifier;
use App\Enums\Workflow\Status;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Events\WorkflowAction\WorkflowTriggerActionTriggered;
use App\Services\GoogleCalendar\CalendarEventObserver;

class DeployWorkflow
{
    public function handle(WorkflowDeploymentTriggered $event): void
    {
        $workflow = $event->workflow;

        if ($workflow->status !== Status::Tested) {
            return;
        }

        $trigger = $workflow->trigger;

        if (Identifier::isGoogleTrigger($trigger->serviceAction->identifier)) {
            $user = $workflow->user;
            /** @var \App\Models\ServiceSubscription $serviceSubscription */
            $serviceSubscription = $user->serviceSubscriptions()
                ->where('service_id', $workflow->trigger->serviceAction->service->id)
                ->first();
            $oauthToken = $serviceSubscription->oauthToken;

            CalendarEventObserver::make($workflow->trigger, $oauthToken)
                ->createOrRefreshSyncToken();
        }

        WorkflowTriggerActionTriggered::dispatch($trigger);

        $workflow->setAsDeployed();
    }
}
