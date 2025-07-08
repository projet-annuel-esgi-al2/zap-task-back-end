<?php

namespace App\Http\Controllers;

use App\Enums\Workflow\Status;
use App\Http\Requests\StoreWorkflowRequest;
use App\Http\Resources\Api\WorkflowResource;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use App\Services\ParameterResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WorkflowController extends Controller
{
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

    /**
     * @group Workflows
     *
     * Fetch A Workflow And Its Actions If Present
     *
     * @authenticated
     *
     * @apiResource \App\Http\Resources\Api\WorkflowResource
     *
     * @apiResourceModel \App\Models\Workflow with=actions
     *
     * @response 200
     *
     * */
    public function show(Workflow $workflow): JsonResponse
    {
        return response()->json(WorkflowResource::make($workflow->load(['actions'])));
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

        if ($request->has('actions')) {
            self::createOrUpdateActions($workflow, $request->input('actions'));
        }

        $workflow->update([
            'name' => $request->input('name'),
        ]);

        $workflow->load('actions.serviceAction.service');

        return response()->json(WorkflowResource::make($workflow));
    }

    private static function createOrUpdateActions(Workflow $workflow, array $actions): void
    {
        $actions = array_map(fn ($action) => array_merge($action, ['workflow_id' => $workflow->id]), $actions);

        if ($workflow->actions->isEmpty()) {
            self::createWorkflowActions($workflow, $actions);

        } else {
            self::upsertWorkflowActions($actions);
        }

        $workflow->load([
            'actions.serviceAction',
            'actions.workflow',
        ]);

        $workflow->actions->each(function (WorkflowAction $action) {
            $parameterResolver = ParameterResolver::make($action);

            $action->body_parameters = array_merge($parameterResolver->resolveBodyParameters(), $action->body_parameters);
            $action->query_parameters = array_merge($parameterResolver->resolveQueryParameters(), $action->query_parameters);
            $action->url_parameters = array_merge($parameterResolver->resolveUrlParameters(), $action->url_parameters);
            $action->headers = array_merge($parameterResolver->resolveHeaders(), $action->headers);

            $action->save();
        });
    }

    private static function createWorkflowActions(Workflow $workflow, array $actions): void
    {
        $actionModels = WorkflowAction::fromApiRequest($actions)
            ->map(fn ($action) => new WorkflowAction($action));

        $workflow->actions()->saveMany($actionModels);
    }

    private static function upsertWorkflowActions(array $actions): void
    {
        $actions = WorkflowAction::fromApiRequest($actions)
            ->map(function ($action) {
                /** @phpstan-ignore-next-line */
                return collect(WorkflowAction::make($action)->getAttributes()) // Force mutators to run to format url with url parameters
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
            update: ['workflow_id', 'service_action_id', 'url', 'execution_order', 'body_parameters', 'query_parameters', 'url_parameters']
        );
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
