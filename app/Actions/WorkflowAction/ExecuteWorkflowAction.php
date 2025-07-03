<?php

namespace App\Actions\WorkflowAction;

use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Http\Integrations\Workflow\Requests\WorkflowActionRequest;
use App\Http\Integrations\Workflow\ServiceConnector;
use App\Models\WorkflowAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class ExecuteWorkflowAction implements ShouldQueue, ShouldQueueAfterCommit
{
    use AsAction;
    use Queueable;

    public function handle(WorkflowAction $action): void
    {
        try {
            $response = ServiceConnector::make()
                ->send(WorkflowActionRequest::fromWorkflowAction($action));

            if ($response->successful()) {
                WorkflowActionExecuted::dispatch($action, (string) $response->status(), $response->successful());
            } else {
                WorkflowActionExecuted::dispatch($action, (string) $response->status(), false, $response->toException()->getMessage());
            }
            error_log('asdfasdfasfd');
        } catch (FatalRequestException|RequestException $e) {
            error_log($e->getStatus());
            WorkflowActionExecuted::dispatch($action, (string) $e->getStatus(), false, $e->getMessage());

            throw $e;
        }
    }

    public function asJob(WorkflowAction $action): void
    {
        $this->handle($action);
    }

    public function asController(Request $request): void
    {
        $action = new WorkflowAction;
        $action->forceFill([

        ]);
    }
}
