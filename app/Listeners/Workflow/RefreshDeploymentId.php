<?php

namespace App\Listeners\Workflow;

use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Services\ParameterResolver\WorkflowAction\ParameterResolver;

class RefreshDeploymentId
{
    public function handle(WorkflowDeploymentTriggered $event): void
    {
        $workflow = $event->workflow;
        $workflow->refreshDeploymentId();

        $parameterResolver = ParameterResolver::make($workflow->trigger);
        $bodyParametersWithNewWebhookAddress = $parameterResolver->resolveBodyParameters();
        $bodyWithNewWebhookAddress = $parameterResolver->resolveBody();

        $workflow->trigger->update([
            'body_parameters' => $bodyParametersWithNewWebhookAddress,
            'resolved_body' => $bodyWithNewWebhookAddress,
        ]);
    }
}
