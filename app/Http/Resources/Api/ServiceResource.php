<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Service
 * */
class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'identifier' => $this->identifier->value,
            'name' => $this->name,
            'actions' => $this->whenLoaded('actions', fn () => ServiceActionResource::collection($this->actions)),
        ];
    }
}
