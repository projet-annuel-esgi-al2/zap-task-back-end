<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

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
        $areServiceActionsLoaded = $this->whenLoaded(
            'actions',
            fn () => $this->actions->first()?->relationLoaded('serviceAction') ?? false,
            false
        );

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status->value,
            'actions' => $this->when($areServiceActionsLoaded, fn () => WorkflowActionResource::collection($this->actions)),
            'saved_at' => $this->saved_at,
            'deployed_at' => $this->deployed_at,
        ];
    }
}
