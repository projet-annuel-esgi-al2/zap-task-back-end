<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Actions\WorkflowAction;

use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Events\WorkflowAction\WorkflowTriggerActionTriggered;
use App\Http\Integrations\Workflow\Exceptions\OAuthTokenExpiredException;
use App\Http\Integrations\Workflow\Requests\WorkflowActionRequest;
use App\Http\Integrations\Workflow\ServiceConnector;
use App\Models\WorkflowAction;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Lorisleiva\Actions\Concerns\AsAction;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class ExecuteWorkflowAction
{
    use AsAction;
    use SerializesModels;

    public function handle(WorkflowAction $action): void
    {
        $action->update([
            'last_executed_at' => now(),
        ]);

        try {
            $response = ServiceConnector::make()
                ->send(WorkflowActionRequest::fromWorkflowAction($action));

            if ($response->successful()) {
                $action->setAsTested();
                CreateWorkflowActionHistory::run(new WorkflowActionExecuted($action, (string) $response->status(), $response->successful()));
            } else {
                $action->setAsError();
                CreateWorkflowActionHistory::run(new WorkflowActionExecuted($action, (string) $response->status(), false, $response->toException()->getMessage()));
            }
        } catch (OAuthTokenExpiredException $exception) {
            $action->setAsError();
            CreateWorkflowActionHistory::run(new WorkflowActionExecuted($action, '500', false, $exception->getMessage()));
        } catch (FatalRequestException|RequestException $e) {
            $action->setAsError();
            CreateWorkflowActionHistory::run(new WorkflowActionExecuted($action, (string) $e->getStatus(), false, $e->getMessage()));

            throw $e;
        }
    }

    public function asJob(WorkflowAction $action): void
    {
        $this->handle($action);
    }

    public function asListener(WorkflowActionTriggered|WorkflowTriggerActionTriggered $actionTriggered): void
    {
        $this->handle($actionTriggered->action);
    }

    public function asController(Request $request): void
    {
        $action = new WorkflowAction;
        $action->forceFill([

        ]);
    }
}
