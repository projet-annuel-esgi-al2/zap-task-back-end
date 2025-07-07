<?php

namespace App\Http\Webhooks\Jobs\Workflow;

use App\Actions\Workflow\ExecuteWorkflow;
use App\Models\Workflow;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessGoogleWebhook extends ProcessWebhookJob
{
    public function handle(): void
    {
        $workflowId = $this->webhookCall->headers()->get('x-goog-channel-token');

        $workflow = Workflow::findOrFail($workflowId);

        ExecuteWorkflow::dispatch($workflow);
    }
}
