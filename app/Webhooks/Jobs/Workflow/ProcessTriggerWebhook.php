<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Webhooks\Jobs\Workflow;

use App\Actions\Workflow\ExecuteWorkflow;
use App\Enums\ServiceAction\Identifier;
use App\Models\Workflow;
use Illuminate\Support\Uri;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessTriggerWebhook extends ProcessWebhookJob
{
    public function handle(): void
    {
        $workflowId = Uri::of($this->webhookCall->url)
            ->query()
            ->get('w');
        $deploymentId = Uri::of($this->webhookCall->url)
            ->query()
            ->get('d');

        $workflow = Workflow::where('id', $workflowId)
            ->where('deployment_id', $deploymentId)
            ->firstOrFail();

        if ($workflow->trigger->serviceAction->identifier === Identifier::GoogleCalendarEventCreated) {
            //            handle syncToken refresh here
        }

        ExecuteWorkflow::dispatch($workflow);
    }
}
