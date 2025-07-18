<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Controllers;

use App\Actions\Workflow\SetWorkflowAsTestedIfPossible;
use App\Enums\Workflow\Status;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Http\Requests\StoreWorkflowRequest;
use App\Http\Resources\Api\WorkflowActionHistoryResource;
use App\Http\Resources\Api\WorkflowResource;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

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

        /** @var Workflow $workflow */
        $workflow->setAsUndeployed();
        $workflow->setAsSaved();
        SetWorkflowAsTestedIfPossible::run($workflow);

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
     * @response 418
     *
     * */
    public function deploy(Workflow $workflow): JsonResponse
    {
        WorkflowDeploymentTriggered::dispatch($workflow);

        $trigger = $workflow->trigger;
        if (! empty($trigger->refresh()->latestExecution)) {
            if (Str::of($trigger->latestExecution->response_http_code)->startsWith('2')) {
                return response()->json(WorkflowActionHistoryResource::make($trigger->latestExecution));
            } else {
                return response()->json(WorkflowActionHistoryResource::make($trigger->latestExecution), Response::HTTP_I_AM_A_TEAPOT);
            }
        }

        return response()->json();
    }

    /**
     * @group Workflows
     *
     * Undeploy A Workflow
     *
     * @authenticated
     *
     * @response 200
     *
     * */
    public function undeploy(Workflow $workflow): JsonResponse
    {
        if ($workflow->status !== Status::Deployed) {
            return response()->json('Cannot undeploy a workflow with status = '.$workflow->status->value, Response::HTTP_BAD_REQUEST);
        }

        $workflow->setAsUndeployed();

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
        $workflow->actions->each(fn (WorkflowAction $action) => $action->history()->delete());
        $workflow->actions->each->delete();
        $workflow->delete();

        return response()->json(null);
    }
}
