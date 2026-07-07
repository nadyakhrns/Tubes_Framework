<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_cannot_access_another_instructors_course(): void
    {
        $owner = User::factory()->instructor()->create();
        $otherInstructor = User::factory()->instructor()->create();
        $course = Course::factory()->create([
            'instructor_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherInstructor)
            ->get(route('instructor.courses.edit', ['course' => $course]));

        $response->assertForbidden();
    }

    public function test_student_cannot_access_another_students_enrollment_lesson(): void
    {
        $studentA = User::factory()->student()->create();
        $studentB = User::factory()->student()->create();
        $course = Course::factory()->create([
            'instructor_id' => User::factory()->instructor()->create()->id,
            'is_published' => true,
            'approval_status' => 'approved',
        ]);
        $enrollment = Enrollment::factory()->create([
            'user_id' => $studentA->id,
            'course_id' => $course->id,
        ]);
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($studentB)
            ->get(route('student.learning.show', ['enrollment' => $enrollment, 'lesson' => $lesson]));

        $response->assertForbidden();
    }
}
