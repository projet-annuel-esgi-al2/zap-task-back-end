<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Controllers;

use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Http\Resources\Api\WorkflowActionHistoryResource;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;

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

        if (! empty($action->refresh()->latestExecution)) {
            return response()->json(WorkflowActionHistoryResource::make($action->latestExecution));
        }

        return response()->json();
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
