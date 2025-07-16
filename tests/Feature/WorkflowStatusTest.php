<?php

namespace Tests\Feature;

use App\Actions\Workflow\ExecuteWorkflow;
use App\Enums\ServiceAction\Type;
use App\Enums\Workflow\Status as WorkflowStatus;
use App\Enums\WorkflowAction\Status as WorkflowActionStatus;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Http\Integrations\Workflow\Requests\WorkflowActionRequest;
use App\Models\OAuthToken;
use App\Models\ServiceAction;
use App\Models\ServiceSubscription;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;
use Tests\Traits\HasLoggedInUser;

class WorkflowStatusTest extends TestCase
{
    use HasLoggedInUser;
    use RefreshDatabase;

    public function test_automatically_sets_tested_on_deployment(): void
    {
        $mockClient = Saloon::fake([
            WorkflowActionRequest::class => MockResponse::make([], 200, []),
        ]);

        /** Creation implemented this way to prevent WorkflowFactory::afterCreating from running */
        /** @var Workflow $workflow */
        $workflow = tap(Workflow::factory()->makeOne([
            'user_id' => $this->user->id,
            'status' => WorkflowStatus::Saved,
        ]), fn (Workflow $workflow) => $workflow->save());

        $trigger = WorkflowAction::factory()->createOne([
            'status' => WorkflowActionStatus::Tested,
            'service_action_id' => ServiceAction::factory()->createOne([
                'type' => Type::Trigger,
            ])->id,
            'workflow_id' => $workflow->id,
            'resolved_body' => [],
        ]);
        $workflowActions = WorkflowAction::factory()->count(4)->create([
            'status' => WorkflowActionStatus::Tested,
            'service_action_id' => ServiceAction::factory()->createOne([
                'type' => Type::Action,
            ])->id,
            'workflow_id' => $workflow->id,
            'resolved_body' => [],
        ]);

        $workflowActions->merge([$trigger])->each(function (WorkflowAction $action) {
            ServiceSubscription::factory()->createOne([
                'service_id' => $action->serviceAction->service->id,
                'oauth_token_id' => OAuthToken::factory()->createOne([
                    'user_id' => $this->user->id,
                ]),
            ]);
        });

        WorkflowDeploymentTriggered::dispatch($workflow);
        $mockClient->assertSent(WorkflowActionRequest::class);

        ExecuteWorkflow::dispatch($workflow);

        $mockClient->assertSentCount(5, WorkflowActionRequest::class);

        $this->assertEquals($workflow->status, WorkflowStatus::Deployed);
    }
}
