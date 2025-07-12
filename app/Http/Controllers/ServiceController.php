<?php

namespace App\Http\Controllers;

use App\Enums\Service\Identifier;
use App\Http\Resources\Api\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * @group Services and Actions
     *
     * Fetch all available services
     */
    public function index(Request $request): JsonResponse
    {
        return Service::all()
            ->toResourceCollection(ServiceResource::class)
            ->toResponse($request);
    }

    /**
     * @group Services and Actions
     * Fetch actions for a specified Service
     * */
    public function get(Request $request, Identifier $serviceIdentifier): JsonResponse
    {
        return Service::with('actions')
            ->where('identifier', $serviceIdentifier)
            ->first()
            ->toResource(ServiceResource::class)
            ->toResponse($request);
    }

    /**
     * @group Services and Actions
     *
     * Fetch Service Subscriptions
     *
     * @authenticated
     *
     * @apiResourceCollection \App\Http\Resources\Api\ServiceResource
     *
     * @apiResourceModel \App\Models\Service
     *
     * @response 200
     */
    public function subscriptions(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return $user
            ->serviceSubscriptions()
            ->with('service')
            ->get()
            ->pluck('service')
            ->toResourceCollection(ServiceResource::class)
            ->toResponse($request);
    }
}
