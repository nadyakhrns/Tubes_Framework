<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\View\View;

class MyCourseController extends Controller
{
    public function index(): View
    {
        $enrollments = Enrollment::query()
            ->with(['course' => fn ($query) => $query->with(['category', 'instructor'])])
            ->where('user_id', auth()->id())
            ->latest('enrolled_at')
            ->paginate(6)
            ->withQueryString();

        return view('student.courses.my-courses', compact('enrollments'));
    }
}
