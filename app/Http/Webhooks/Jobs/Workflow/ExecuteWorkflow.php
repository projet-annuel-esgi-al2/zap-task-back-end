<?php

namespace App\Http\Webhooks\Jobs\Workflow;

use App\Models\Workflow;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ExecuteWorkflow extends ProcessWebhookJob
{
    public function handle(): void
    {
        // execute the workflow here
        //        error_log(json_encode($this->webhookCall->headers()->all()));
        error_log(json_encode($this->webhookCall->payload));
        $workflow = Workflow::find($this->webhookCall->headers()->get('x-goog-channel-token'));
        error_log($workflow->id);
    }
}
