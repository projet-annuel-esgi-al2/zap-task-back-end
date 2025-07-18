<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Controllers;

use App\Actions\Workflow\SetWorkflowAsTestedIfPossible;
use App\Enums\WorkflowAction\Status;
use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Http\Resources\Api\WorkflowResource;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WorkflowActionController extends Controller
{
    public function create(): JsonResponse
    {
        return response()->json();
    }

    /**
     * @group Services and Actions
     *
     * Test An Action
     *
     * @authenticated

     *
     * @apiResource \App\Http\Resources\Api\WorkflowActionHistoryResource
     *
     * @apiResourceModel \App\Models\WorkflowActionHistory with=workflowAction.serviceAction
     *
     * @response 200
     */
    public function execute(WorkflowAction $action): JsonResponse
    {
        WorkflowActionTriggered::dispatch($action);

        $action->workflow->setAsUndeployed();
        $action->workflow->setAsSaved();
        SetWorkflowAsTestedIfPossible::run($action->workflow);

        if ($action->status === Status::Tested) {
            return response()->json(WorkflowResource::make($action->workflow));
        }

        return response()->json(WorkflowResource::make($action->workflow), Response::HTTP_I_AM_A_TEAPOT);
    }

    /**
     * @group Services and Actions
     *
     * Delete an action
     *
     * @authenticated
     *
     * @response 200
     */
    public function delete(WorkflowAction $action): JsonResponse
    {
        $action->history()->delete();

        $action->delete();

        return response()->json();
    }
}
