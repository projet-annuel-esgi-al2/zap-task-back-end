<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Factories;

use App\Enums\WorkflowAction\Status;
use App\Models\ServiceAction;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Factories\Factory;
use Saloon\Enums\Method;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowAction>
 */
class WorkflowActionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'workflow_id' => Workflow::factory(),
            'service_action_id' => ServiceAction::factory(),
            'status' => Status::randomCase(),
            'execution_order' => abs($this->faker->randomDigit()),
            'url' => $this->faker->url(),
            'body_parameters' => [],
            'url_parameters' => [],
            'query_parameters' => [],
            'http_method' => Method::POST,
            'last_executed_at' => $this->faker->dateTime(),
        ];
    }
}
