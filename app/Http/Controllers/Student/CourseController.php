<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EnrollmentRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->with(['category', 'instructor'])
            ->withCount('lessons')
            ->where('is_published', true)
            ->where('approval_status', 'approved')
            ->when(request('search'), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when(request('category_id'), fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('student.courses.index', [
            'courses' => $courses,
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function show(Course $course): View
    {
        $this->authorize('view', $course);

        $course->load(['category', 'instructor', 'sections' => fn ($query) => $query->with('lessons')->orderBy('order'), 'lessons', 'quizzes' => fn ($query) => $query->where('is_published', true)]);

        $isEnrolled = Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
        $userEnrollment = Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->first();

        return view('student.courses.show', compact('course', 'isEnrolled', 'userEnrollment'));
    }

    public function enroll(EnrollmentRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('enroll', $course);

        Enrollment::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $course->id,
            ],
            [
                'status' => 'active',
                'enrolled_at' => now(),
            ]
        );

        return redirect()->route('student.my-courses')->with('success', 'You have enrolled in this course.');
    }
}
