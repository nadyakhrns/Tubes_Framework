<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizAttempt>
 */
class QuizAttemptFactory extends Factory
{
    protected $model = QuizAttempt::class;

    public function definition(): array
    {
        $totalQuestions = fake()->numberBetween(3, 10);
        $correctAnswers = fake()->numberBetween(0, $totalQuestions);

        return [
            'quiz_id' => Quiz::factory(),
            'user_id' => User::factory()->student(),
            'score' => ($correctAnswers / $totalQuestions) * 100,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'passed' => $correctAnswers >= ceil($totalQuestions * 0.7),
            'started_at' => now()->subMinutes(fake()->numberBetween(5, 60)),
            'submitted_at' => now()->subMinutes(fake()->numberBetween(1, 30)),
        ];
    }
}
