<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;

class WorkflowStatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $workflows = Workflow::with([
            'actions.history.workflowAction',
            'actions.serviceAction.service',
        ])
            ->where('user_id', $user->id)
            ->get();

        $totalWorkflows = $workflows->count();

        $workflowsByStatus = $workflows
            ->groupBy('status')
            ->mapWithKeys(function ($group, $status) use ($totalWorkflows) {
                $count = $group->count();

                return [
                    $status => [
                        'count' => $count,
                        'percent' => round(($count / max($totalWorkflows, 1)) * 100, 1),
                    ],
                ];
            });

        $workflowsByMonth = $workflows
            ->groupBy(fn ($workflow) => optional($workflow->created_at)->format('Y-m'))
            ->map(fn ($group) => $group->count());

        $actions = $workflows->flatMap->actions;
        $totalActions = $actions->count();

        $actionsByStatus = $actions
            ->groupBy('status')
            ->mapWithKeys(function ($group, $status) use ($totalActions) {
                $count = $group->count();

                return [
                    $status => [
                        'count' => $count,
                        'percent' => round(($count / max($totalActions, 1)) * 100, 1),
                    ],
                ];
            });

        $executions = $actions->flatMap->history;
        $totalExecutions = $executions->count();

        $executionsByStatus = $executions
            ->groupBy('execution_status')
            ->mapWithKeys(function ($group, $status) use ($totalExecutions) {
                $count = $group->count();

                return [
                    $status => [
                        'count' => $count,
                        'percent' => round(($count / max($totalExecutions, 1)) * 100, 1),
                    ],
                ];
            });

        $lastErrors = $executions
            ->whereNotNull('exception')
            ->sortByDesc('executed_at')
            ->take(5)
            ->map(fn ($e) => [
                'action_id' => $e->workflow_action_id,
                'message' => $e->exception,
                'executed_at' => $e->executed_at,
            ])
            ->values();

        $usedServices = $actions
            ->filter(fn ($a) => $a->serviceAction && $a->serviceAction->service)
            ->map(fn ($a) => $a->serviceAction->service->name)
            ->unique()
            ->values();

        return response()->json([
            'workflows' => [
                'total' => $totalWorkflows,
                'by_status' => $workflowsByStatus,
                'by_month' => $workflowsByMonth,
            ],
            'actions' => [
                'total' => $totalActions,
                'by_status' => $actionsByStatus,
            ],
            'executions' => [
                'total' => $totalExecutions,
                'by_status' => $executionsByStatus,
                'last_errors' => $lastErrors,
            ],
            'used_services' => $usedServices,
        ]);
    }
}
