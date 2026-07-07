<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'instructor_id' => User::factory()->instructor(),
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(),
            'description' => fake()->paragraph(4),
            'learning_objectives' => fake()->paragraph(),
            'thumbnail' => fake()->imageUrl(640, 480, 'course', true),
            'is_published' => fake()->boolean(80),
        ];
    }
}
