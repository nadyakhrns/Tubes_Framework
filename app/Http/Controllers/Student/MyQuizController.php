<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\View\View;

class MyQuizController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        // Get all published quizzes that belong to a valid course
        $quizzes = Quiz::where('is_published', true)
            ->whereHas('course')
            ->with(['course.enrollments' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->latest()
            ->paginate(10);

        // Get student's enrolled course IDs
        $enrolledCourseIds = Enrollment::where('user_id', $userId)->pluck('course_id')->toArray();

        // Get attempts to check statuses
        $quizIds = $quizzes->pluck('id');
        $attempts = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->where('user_id', $userId)
            ->get()
            ->keyBy('quiz_id');

        return view('student.quizzes.index', compact('quizzes', 'attempts', 'enrolledCourseIds'));
    }
}
