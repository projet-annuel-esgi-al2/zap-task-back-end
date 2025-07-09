<?php

namespace Tests\Feature\Controller\Workflow;

use App\Models\ServiceAction;
use App\Models\Workflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\HasLoggedInUser;

class WorkflowControllerTest extends TestCase
{
    use HasLoggedInUser;
    use RefreshDatabase;
    use WithFaker;

    public function test_can_create_empty_workflow(): void
    {
        $response = $this->put(
            '/api/workflows',
            data: [
                'name' => 'Untitled Workflow',
            ],
        )->assertStatus(200);

        $createdWorkflow = $this->user->workflows->first();

        $response->assertJson([
            'id' => $createdWorkflow->id,
            'name' => $createdWorkflow->name,
            'status' => $createdWorkflow->status->value,
            'saved_at' => $createdWorkflow->saved_at,
            'deployed_at' => $createdWorkflow->deployed_at,
        ]);
    }

    public function test_cannot_create_workflow_without_a_name(): void
    {
        $this->put(
            '/api/workflows',
        )->assertStatus(302);
    }

    public function test_can_create_workflow_with_actions(): void
    {
        $response = $this->put(
            '/api/workflows',
            data : [
                'name' => 'Workflow Test Name',
            ]
        )->assertStatus(200);

        /** @var \App\Models\ServiceAction $serviceAction */
        $serviceAction = ServiceAction::factory()
            ->createOne([
                'url' => 'https://test.url.com/{calendarId}',
                'url_parameters' => [
                    [
                        'parameter_key' => 'calendarId',
                        'parameter_type' => 'select',
                        'parameter_value' => '{{$calendarId}}',
                        'options' => [
                            'option1',
                            'option2',
                        ],
                    ],
                ],
            ]);
        $actions = [
            [
                'service_action_id' => $serviceAction->id,
                'parameters' => [
                    'calendarId' => 'option1',
                ],
                'execution_order' => 0,
            ],
        ];

        $updateWorkflowResponse = $this->put(
            '/api/workflows/'.$response->json('id'),
            data: [
                'name' => 'Workflow Test Name',
                'actions' => $actions,
            ]
        );

        /** @var \App\Models\Workflow $workflow */
        $workflow = Workflow::first();
        /** @var \App\Models\WorkflowAction $workflowAction */
        $workflowAction = $workflow->actions->first();

        $updateWorkflowResponse->assertJson([
            'id' => $response->json('id'),
            'name' => $workflow->name,
            'status' => $workflow->status->value,
            'actions' => [
                [
                    'workflow_id' => $response->json('id'),
                    'service' => [
                        'identifier' => $serviceAction->service->identifier->value,
                        'name' => $serviceAction->service->name,
                    ],
                    'identifier' => $serviceAction->identifier->value,
                    'name' => $serviceAction->name,
                    'type' => $serviceAction->type->value,
                    'status' => $workflowAction->status->value,
                    'execution_order' => $workflowAction->execution_order,
                    'url' => $workflowAction->url,
                    'parameters' => $workflowAction->getParametersForApi(),
                    'last_executed_at' => $workflowAction->last_executed_at,
                ],
            ],
        ]);
    }

    public function test_returns_bad_request_when_workflow_id_not_present_and_action_id_present(): void
    {
        /** @var \App\Models\ServiceAction $serviceAction */
        $serviceAction = ServiceAction::factory()
            ->createOne([
                'url' => 'https://test.url.com/{calendarId}',
                'url_parameters' => [
                    [
                        'parameter_key' => 'calendarId',
                        'parameter_type' => 'select',
                        'options' => [
                            'option1',
                            'option2',
                        ],
                    ],
                ],
            ]);
        $actions = [
            [
                'id' => $this->faker->uuid(),
                'service_action_id' => $serviceAction->id,
                'parameters' => [
                    'calendarId' => 'option1',
                ],
                'execution_order' => 0,
            ],
        ];

        $this->put(
            '/api/workflows/',
            data: [
                'name' => 'Workflow Test Name',
                'actions' => $actions,
            ]
        )->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
