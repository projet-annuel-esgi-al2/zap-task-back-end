<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Laravel\Sanctum\PersonalAccessToken
 * */
class PersonalAccessTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tokenable_type' => $this->tokenable_type,
            'tokenable_id' => $this->tokenable_id,
            'name' => $this->name,
            'token' => $this->token,
            'abilities' => $this->abilities,
            'last_used_at' => $this->last_used_at,
            'expires_at' => $this->expires_at,
        ];
    }
}
