<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $instructors = User::factory()->instructor()->count(3)->create();
        $students = User::factory()->student()->count(12)->create();

        $categories = Category::factory()->count(5)->create();

        $courses = collect();
        foreach ($categories as $category) {
            $courses = $courses->merge(
                Course::factory()->count(2)->create([
                    'category_id' => $category->id,
                    'instructor_id' => $instructors->random()->id,
                ]),
            );
        }

        foreach ($courses as $course) {
            $sections = Section::factory()->count(3)->create([
                'course_id' => $course->id,
            ]);

            foreach ($sections as $index => $section) {
                Lesson::factory()->count(3)->create([
                    'course_id' => $course->id,
                    'section_id' => $section->id,
                    'order' => $index + 1,
                ]);
            }

            $availableStudents = $students->shuffle()->take(4);

            foreach ($availableStudents as $student) {
                Enrollment::factory()->create([
                    'course_id' => $course->id,
                    'user_id' => $student->id,
                ]);
            }

            $quiz = Quiz::factory()->create([
                'course_id' => $course->id,
            ]);

            $questions = Question::factory()->count(4)->create([
                'quiz_id' => $quiz->id,
            ]);

            foreach ($questions as $question) {
                $options = Option::factory()->count(4)->create([
                    'question_id' => $question->id,
                ]);

                $options->random()->update(['is_correct' => true]);
            }

            $quiz->load('questions.options');

            foreach ($students->random(3) as $student) {
                $attempt = QuizAttempt::factory()->create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $student->id,
                ]);

                foreach ($quiz->questions as $question) {
                    $selectedOption = $question->options->random();

                    QuizAnswer::factory()->create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'option_id' => $selectedOption->id,
                        'is_correct' => $selectedOption->is_correct,
                    ]);
                }
            }
        }
    }
}
