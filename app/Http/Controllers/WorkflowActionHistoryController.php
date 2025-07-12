<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\WorkflowActionHistoryResource;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;

class WorkflowActionHistoryController extends Controller
{
    public function show(Workflow $workflow): JsonResponse
    {
        $logs = $workflow->actions()
            ->with([
                'history.workflowAction.serviceAction.service',
            ])
            ->get()
            ->flatMap(fn (WorkflowAction $action) => $action->history)
            ->sortBy('executed_at')
            ->values();

        return response()->json([
            'workflow_id' => $workflow->id,
            'workflow_name' => $workflow->name,

            'logs' => WorkflowActionHistoryResource::collection($logs),
        ]);
    }
}
