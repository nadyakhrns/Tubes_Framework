<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'section_id' => Section::factory(),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraph(4),
            'video_url' => fake()->url(),
            'duration_minutes' => fake()->numberBetween(5, 45),
            'order' => fake()->numberBetween(1, 10),
            'is_published' => true,
        ];
    }
}
