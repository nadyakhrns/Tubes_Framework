<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'course_id' => Course::factory(),
            'status' => fake()->randomElement(['active', 'completed', 'paused']),
            'enrolled_at' => now()->subDays(fake()->numberBetween(1, 120)),
            'completed_at' => fake()->optional()->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
