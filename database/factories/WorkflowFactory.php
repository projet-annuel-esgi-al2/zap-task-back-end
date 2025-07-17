<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Factories;

use App\Enums\Workflow\Status;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workflow>
 */
class WorkflowFactory extends Factory
{
    public function definition(): array
    {
        $id = $this->faker->uuid();

        return [
            'id' => $id,
            'user_id' => User::factory()->loggedIn(),
            'name' => $this->faker->name(),
            'status' => Status::randomCase(),
            'saved_at' => $this->faker->dateTime(),
            'deployed_at' => $this->faker->dateTime(),
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Workflow $workflow) {
            WorkflowAction::factory()->count(2)->create([
                'workflow_id' => $workflow->id,
                'body_parameters' => [
                    [
                        'parameter_name' => 'Calendar',
                        'parameter_key' => 'calendarId',
                        'parameter_type' => 'select',
                        'options' => [
                            'calendar-id-1',
                            'calendar-id-2',
                        ],
                    ],
                    [
                        'parameter_name' => 'Example',
                        'parameter_key' => 'example-key',
                        'parameter_type' => 'checkbox',
                    ],
                ],
            ]);
        });
    }
}
