<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Factories;

use App\Enums\WorkflowActionHistory\ExecutionStatus;
use App\Models\WorkflowAction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Response;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowActionHistory>
 */
class WorkflowActionHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $executionStatus = ExecutionStatus::randomValue();
        $responseHttpCode = $executionStatus === ExecutionStatus::Success->value
            ? $this->faker->randomElement([
                Response::HTTP_OK,
                Response::HTTP_FOUND,
            ])
            : $this->faker->randomElement([
                Response::HTTP_BAD_REQUEST,
                Response::HTTP_UNAUTHORIZED,
                Response::HTTP_NOT_FOUND,
                Response::HTTP_FORBIDDEN,
                Response::HTTP_SERVICE_UNAVAILABLE,
            ]);

        return [
            'id' => $this->faker->uuid(),
            'workflow_action_id' => WorkflowAction::factory(),
            'execution_status' => $executionStatus,
            'response_http_code' => $responseHttpCode,
            'execution_order' => abs($this->faker->randomDigit()),
            'exception' => $this->faker->text(),
            'url' => $this->faker->url(),
            'parameters' => [],
            'executed_at' => $this->faker->dateTime(),
        ];
    }
}
