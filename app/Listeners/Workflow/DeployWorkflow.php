<?php

namespace App\Listeners\Workflow;

use App\Enums\ServiceAction\Identifier;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Services\GoogleCalendar\CalendarEventObserver;

class DeployWorkflow
{
    public function handle(WorkflowDeploymentTriggered $event): void
    {
        $workflow = $event->workflow;
        $trigger = $workflow->trigger;

        if ($trigger->serviceAction->identifier === Identifier::GoogleCalendarEventCreated) {
            $user = $workflow->user;
            /** @var \App\Models\ServiceSubscription $serviceSubscription */
            $serviceSubscription = $user->serviceSubscriptions()
                ->where('service_id', $workflow->trigger->serviceAction->service->id)
                ->first();
            $oauthToken = $serviceSubscription->oauthToken;

            CalendarEventObserver::make($workflow->trigger, $oauthToken)
                ->createOrRefreshSyncToken();
        }

        WorkflowActionTriggered::dispatch($trigger);

        $workflow->setAsDeployed();
    }
}
