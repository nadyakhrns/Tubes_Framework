<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Programming',
                'Design',
                'Business',
                'Marketing',
                'Data Science',
                'Productivity',
            ]),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
