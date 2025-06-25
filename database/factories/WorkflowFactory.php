<?php

namespace Database\Factories;

use App\Enums\Workflow\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workflow>
 */
class WorkflowFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'status' => Status::randomCase(),
            'saved_at' => $this->faker->dateTime(),
            'deployed_at' => $this->faker->dateTime(),
        ];
    }
}
