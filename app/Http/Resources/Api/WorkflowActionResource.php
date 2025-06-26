<?php

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
        return [
            'id' => $this->id,
            'workflow_id' => $this->workflow_id,
            'service' => $this->whenLoaded('serviceAction', fn () => ServiceResource::make($this->serviceAction->service)),
            'identifier' => $this->whenLoaded('serviceAction', fn () => $this->serviceAction->identifier),
            'name' => $this->whenLoaded('serviceAction', fn () => $this->serviceAction->name),
            'type' => $this->whenLoaded('serviceAction', fn () => $this->serviceAction->type),
            'status' => $this->status->value,
            'execution_order' => $this->execution_order,
            'url' => $this->url,
            'parameters' => $this->parameters_for_api,
            'last_executed_at' => $this->last_executed_at,
        ];
    }
}
