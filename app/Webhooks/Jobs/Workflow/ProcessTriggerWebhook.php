<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Webhooks\Jobs\Workflow;

use App\Actions\Workflow\ExecuteWorkflow;
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

        $workflow = Workflow::findOrFail($workflowId);

        ExecuteWorkflow::dispatch($workflow);
    }
}
