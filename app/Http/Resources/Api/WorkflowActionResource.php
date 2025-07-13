<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\WorkflowAction
 * */
class WorkflowActionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $service = $this->whenLoaded('serviceAction', function () {
            if ($this->serviceAction->relationLoaded('service')) {
                return $this->serviceAction->service;
            }

            return null;
        });

        return [
            'id' => $this->id,
            'workflow_id' => $this->workflow_id,
            'service' => $this->when(! is_null($service), fn () => ServiceResource::make($service)),
            'service_action_id' => $this->whenLoaded('serviceAction', fn () => $this->serviceAction->id),
            'identifier' => $this->whenLoaded('serviceAction', fn () => $this->serviceAction->identifier),
            'name' => $this->whenLoaded('serviceAction', fn () => $this->serviceAction->name),
            'type' => $this->whenLoaded('serviceAction', fn () => $this->serviceAction->type),
            'status' => $this->status->value,
            'execution_order' => $this->execution_order,
            'url' => $this->url,
            'parameters' => $this->whenLoaded('serviceAction', $this->getParametersForApi()),
            'last_executed_at' => $this->last_executed_at,
        ];
    }
}
