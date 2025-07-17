<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Controllers;

use App\Actions\Workflow\SetWorkflowAsTestedIfPossible;
use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Http\Resources\Api\WorkflowActionHistoryResource;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

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

        SetWorkflowAsTestedIfPossible::run($action->workflow);

        if (! empty($action->refresh()->latestExecution)) {
            if (Str::of($action->latestExecution->response_http_code)->startsWith('2')) {
                return response()->json(WorkflowActionHistoryResource::make($action->latestExecution));
            } else {
                return response()->json(WorkflowActionHistoryResource::make($action->latestExecution), Response::HTTP_I_AM_A_TEAPOT);
            }
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
