<?php

namespace App\Actions;

use App\Enums\ServiceAction\Type;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Models\Workflow;
use App\Services\ParameterResolver\WorkflowAction\ParameterResolver;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class RefreshDeploymentId
{
    use AsAction;

    public function handle(Workflow $workflow)
    {
        $workflow->undeploy()
            ->refreshDeploymentId();

        Log::info('before refresh deployment id = '.$workflow->deployment_id);
        $workflow->refresh();
        Log::info('after refresh deployment id = '.$workflow->deployment_id);
        $parameterResolver = ParameterResolver::make($workflow->trigger);
        $bodyParametersWithNewWebhookAddress = $parameterResolver->resolveBodyParameters();
        $bodyWithNewWebhookAddress = $parameterResolver->resolveBody();

        $workflow->trigger->update([
            'body_parameters' => $bodyParametersWithNewWebhookAddress,
            'resolved_body' => $bodyWithNewWebhookAddress,
        ]);
    }

    public function asListener(object $event): void
    {
        if ($event instanceof WorkflowDeploymentTriggered) {
            $this->handle($event->workflow);
        } elseif ($event instanceof WorkflowActionTriggered) {
            if ($event->action->serviceAction->type !== Type::Trigger) {
                return;
            }

            $this->handle($event->action->workflow);
        }
    }
}
