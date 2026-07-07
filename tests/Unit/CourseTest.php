<?php

namespace Tests\Unit;

use App\Models\Course;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CourseTest extends TestCase
{
    #[Test]
    public function it_generates_a_slug_from_the_course_title(): void
    {
        $course = Course::create(['title' => 'Mastering Laravel Basics']);

        $this->assertSame('mastering-laravel-basics', $course->slug);
    }
}
