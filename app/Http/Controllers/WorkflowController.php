<?php

namespace App\Http\Controllers;

use App\Enums\Workflow\Status;
use App\Http\Requests\StoreWorkflowRequest;
use App\Http\Resources\Api\WorkflowResource;
use App\Models\ServiceAction;
use App\Models\Workflow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->workflows()->create([
            'name' => $validated['name'],
            'status' => Status::Draft,
        ]);

        return response()->json();
    }

    public function update(StoreWorkflowRequest $request, Workflow $workflow): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'max:255',
        ]);

        $workflow->update([
            'name' => $validated['name'],
        ]);

        $requestActions = collect($request->input('actions'))
            ->sortBy('execution_order')
            ->values();

        $serviceActions = ServiceAction::all();
        $requestActions->map(function (array $action) {});

        // for each workflow action and request action couple
        // overwrite (update) the workflow action
        // take the remaining requestAction (if any) and create them in the DB
        //        $workflow->actions->each(function (WorkflowAction $action) {
        //            $action->updateOrInsert(
        //                ['service_action_id' => $action], //arguments to see if exists
        //                [], // arguments to update
        //            );
        //        });

        return response()->json(WorkflowResource::make($workflow));
    }

    public function destroy(Workflow $workflow): JsonResponse
    {
        $workflow->delete();

        return response()->json(null);
    }
}
