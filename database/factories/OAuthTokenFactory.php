<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OAuthToken>
 */
class OAuthTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->loggedIn(),
            'value' => $this->faker->text(32),
            'refresh_token' => $this->faker->text(24),
            'expires_at' => now()->addDays(10),
        ];
    }
}
