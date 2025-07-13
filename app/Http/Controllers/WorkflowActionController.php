<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Controllers;

use App\Events\WorkflowActionTriggered;
use App\Http\Resources\Api\WorkflowActionHistoryResource;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;

class WorkflowActionController extends Controller
{
    public function create(): JsonResponse
    {
        return response()->json();
    }

    public function execute(WorkflowAction $action): JsonResponse
    {
        WorkflowActionTriggered::dispatch($action);

        if (! empty($action->refresh()->latestExecution)) {
            return response()->json(WorkflowActionHistoryResource::make($action->latestExecution));
        }

        return response()->json();
    }
}
