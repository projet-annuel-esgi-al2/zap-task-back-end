<?php

namespace App\Http\Controllers;

use App\Actions\WorkflowAction\ExecuteWorkflowAction;
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
        ExecuteWorkflowAction::dispatch($action);

        if (! empty($action->refresh()->latestExecution)) {
            return response()->json(WorkflowActionHistoryResource::make($action->refresh()->latestExecution));
        }

        return response()->json();
    }
}
