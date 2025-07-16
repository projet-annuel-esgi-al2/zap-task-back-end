<?php

namespace Tests\Feature;

use App\Actions\WorkflowAction\ExecuteWorkflowAction;
use App\Enums\WorkflowAction\Status;
use App\Http\Integrations\Workflow\Requests\WorkflowActionRequest;
use App\Models\OAuthToken;
use App\Models\ServiceSubscription;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;
use Tests\Traits\HasLoggedInUser;

class WorkflowActionStatusTest extends TestCase
{
    use HasLoggedInUser;
    use RefreshDatabase;

    public function test_successful_execution_sets_workflow_status_as_tested(): void
    {
        $mockClient = Saloon::fake([
            WorkflowActionRequest::class => MockResponse::make([], 200, []),
        ]);

        $workflowAction = WorkflowAction::factory()->createOne([
            'status' => Status::Draft,
            'workflow_id' => Workflow::factory()->createOne(['user_id' => $this->user->id]),
            'resolved_body' => [],
        ]);
        ServiceSubscription::factory()->createOne([
            'service_id' => $workflowAction->serviceAction->service->id,
            'oauth_token_id' => OAuthToken::factory()->createOne([
                'user_id' => $this->user->id,
            ]),
        ]);

        ExecuteWorkflowAction::run($workflowAction);

        $mockClient->assertSent(WorkflowActionRequest::class);

        $this->assertEquals(Status::Tested, $workflowAction->status);
    }

    public function test_failed_execution_sets_workflow_status_as_error(): void
    {
        $mockClient = Saloon::fake([
            WorkflowActionRequest::class => MockResponse::make([], 400, []),
        ]);

        $workflowAction = WorkflowAction::factory()->createOne([
            'status' => Status::Draft,
            'workflow_id' => Workflow::factory()->createOne(['user_id' => $this->user->id]),
            'resolved_body' => [],
        ]);
        ServiceSubscription::factory()->createOne([
            'service_id' => $workflowAction->serviceAction->service->id,
            'oauth_token_id' => OAuthToken::factory()->createOne([
                'user_id' => $this->user->id,
            ]),
        ]);

        ExecuteWorkflowAction::run($workflowAction);

        $mockClient->assertSent(WorkflowActionRequest::class);

        $this->assertEquals(Status::Error, $workflowAction->status);
    }
}
