<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

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
            'id' => $this->id,
            'name' => $this->name,
            'identifier' => $this->identifier->value,
            'type' => $this->type->value,
            'parameters' => $this->parameters_for_api,
        ];
    }
}
