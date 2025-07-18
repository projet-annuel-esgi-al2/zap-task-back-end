<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

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
            'serviceActions' => $this->whenLoaded('serviceActions', fn () => ServiceActionResource::collection($this->serviceActions)),
            'hasTriggers' => $this->triggers()->exists(),
            'hasActions' => $this->actions()->exists(),
        ];
    }
}
