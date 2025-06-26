<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Workflow
 * */
class WorkflowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status->value,
            'actions' => $this->whenLoaded('actions', fn () => WorkflowActionResource::collection($this->actions)),
            'saved_at' => $this->saved_at,
            'deployed_at' => $this->deployed_at,
        ];
    }
}
