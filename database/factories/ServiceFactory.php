<?php

namespace Database\Factories;

use App\Enums\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $identifier = Service\Identifier::randomValue();
        $name = Str::of($identifier)->headline();

        return [
            'identifier' => $identifier,
            'name' => $name->toString(),
            'socialite_driver_identifier' => $this->faker->text(10),
            'oauth_token_options' => [],
        ];
    }
}
