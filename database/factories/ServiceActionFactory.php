<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Factories;

use App\Enums\ServiceAction\Identifier;
use App\Enums\ServiceAction\TriggerNotificationType;
use App\Enums\ServiceAction\Type;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Saloon\Enums\Method;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceAction>
 */
class ServiceActionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'identifier' => Identifier::randomCase(),
            'service_id' => Service::factory(),
            'type' => Type::randomCase(),
            'url' => $this->faker->url(),
            'body_parameters' => [],
            'url_parameters' => [],
            'query_parameters' => [],
            'http_method' => Method::POST,
            'trigger_notification_type' => TriggerNotificationType::randomCase(),
        ];
    }
}
