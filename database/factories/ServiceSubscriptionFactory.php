<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Factories;

use App\Models\OAuthToken;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceSubscription>
 */
class ServiceSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'service_id' => Service::factory(),
            'oauth_token_id' => OAuthToken::factory(),
        ];
    }
}
