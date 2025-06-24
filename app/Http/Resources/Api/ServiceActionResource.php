<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ServiceAction
 * */
class ServiceActionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'identifier' => $this->identifier->value,
            'type' => $this->type->value,
            'parameters' => $this->parameters,
        ];
    }
}
