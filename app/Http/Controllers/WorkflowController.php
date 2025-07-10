<?php

namespace App\Http\Controllers;

use App\Actions\WorkflowAction\ExecuteWorkflowAction;
use App\Enums\Workflow\Status;
use App\Http\Requests\StoreWorkflowRequest;
use App\Http\Resources\Api\WorkflowActionHistoryResource;
use App\Http\Resources\Api\WorkflowResource;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WorkflowController extends Controller
{
    /**
     * @group Workflows
     *
     * Fetch A Workflow And Its Actions If Present
     *
     * @authenticated
     *
     * @apiResource \App\Http\Resources\Api\WorkflowResource
     *
     * @apiResourceModel \App\Models\Workflow with=actions.serviceAction.service,actions.workflow
     *
     * @response 200
     *
     * */
    public function show(Workflow $workflow): JsonResponse
    {
        $workflow->load([
            'actions.serviceAction.service',
            'actions.workflow',
        ]);

        return response()->json(WorkflowResource::make($workflow));
    }

    /**
     * @group Workflows
     *
     * Fetch User's Workflows
     *
     * @authenticated
     *
     * @apiResourceCollection \App\Http\Resources\Api\WorkflowResource
     *
     * @apiResourceModel \App\Models\Workflow
     *
     * @response 200
     *
     * */
    public function index(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return response()->json(WorkflowResource::collection($user->workflows));
    }

    public function createOrUpdate(StoreWorkflowRequest $request, ?Workflow $workflow = null): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (is_null($workflow) && $request->has('actions')) {
            $actions = collect($request->input('actions'));
            $anyActionHasId = $actions->contains(fn ($action) => isset($action['id']));

            if ($anyActionHasId) {
                return response()
                    ->json(
                        'Request missing url parameter "workflow_id" but contains action with "id"',
                        Response::HTTP_BAD_REQUEST,
                    );
            }
        }

        if (is_null($workflow)) {
            $workflow = $user->workflows()->create([
                'name' => $request->input('name'),
                'status' => Status::Draft,
            ]);
        }

        if ($request->has('actions') && ! empty($request->input('actions'))) {
            self::createOrUpdateActions($workflow, $request->input('actions'));
        }

        $workflow->update([
            'name' => $request->input('name'),
        ]);

        $workflow->load([
            'actions.serviceAction.service',
            'actions.workflow',
        ]);

        return response()->json(WorkflowResource::make($workflow));
    }

    private static function createOrUpdateActions(Workflow $workflow, array $actions): void
    {
        $actions = array_map(fn ($action) => array_merge($action, ['workflow_id' => $workflow->id]), $actions);

        WorkflowAction::createOrUpdateFromApiRequest($actions);
    }

    /**
     * @group Workflows
     *
     * Deploy A Workflow
     *
     * @authenticated
     *
     * @apiResourceCollection \App\Http\Resources\Api\WorkflowActionHistoryResource
     *
     * @apiResourceModel \App\Models\WorkflowActionHistory
     *
     * @response 200
     *
     * */
    public function deploy(Workflow $workflow): JsonResponse
    {
        $trigger = $workflow->trigger;

        ExecuteWorkflowAction::run($trigger);

        if (! empty($trigger->refresh()->latestExecution)) {
            return response()->json(WorkflowActionHistoryResource::make($trigger->latestExecution));
        }

        return response()->json();
    }

    /**
     * @group Workflows
     *
     * Delete A Workflow
     *
     * @authenticated
     *
     * @response 200
     *
     * */
    public function destroy(Workflow $workflow): JsonResponse
    {
        $workflow->delete();

        return response()->json(null);
    }
}
