<?php

namespace App\Http\Controllers;

use App\Enums\Workflow\Status;
use App\Http\Requests\StoreWorkflowRequest;
use App\Http\Resources\Api\WorkflowResource;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WorkflowController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return response()->json(WorkflowResource::collection($user->workflows));
    }

    public function show(Workflow $workflow): JsonResponse
    {
        return response()->json(WorkflowResource::make($workflow->load(['actions'])));
    }

    public function createOrUpdate(StoreWorkflowRequest $request, ?Workflow $workflow = null): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (is_null($workflow)) {
            $workflow = $user->workflows()->create([
                'name' => $request->input('name'),
                'status' => Status::Draft,
            ]);
        }

        if ($request->has('actions')) {
            self::createOrUpdateActions($workflow, $request->input('actions'));
        }

        $workflow->update([
            'name' => $request->input('name'),
        ]);

        return response()->json(WorkflowResource::make($workflow->load('actions')->refresh()));
    }

    private static function createOrUpdateActions(Workflow $workflow, array $actions): void
    {
        $actions = array_map(fn ($action) => array_merge($action, ['workflow_id' => $workflow->id]), $actions);

        if ($workflow->actions->isEmpty()) {
            $actionModels = WorkflowAction::fromApiRequest($actions)
                ->map(fn ($action) => new WorkflowAction($action));

            $workflow->actions()->saveMany($actionModels);

            return;
        }

        // Force mutators to run
        $actions = WorkflowAction::fromApiRequest($actions)
            ->map(function ($action) {
                /** @phpstan-ignore-next-line */
                return collect(WorkflowAction::make($action)->getAttributes())
                    ->map(fn ($attribute) => is_array($attribute) ? json_encode($attribute) : $attribute);
            })
            ->map(function (Collection $action) {
                if (! $action->has('id')) {
                    $action['id'] = Str::uuid()->toString();

                }

                return $action;
            })
            ->toArray();

        WorkflowAction::upsert(
            $actions,
            uniqueBy: ['id'],
            update: ['service_action_id', 'url', 'execution_order', 'body_parameters', 'query_parameters', 'url_parameters']
        );
    }

    public function destroy(Workflow $workflow): JsonResponse
    {
        $workflow->delete();

        return response()->json(null);
    }
}
