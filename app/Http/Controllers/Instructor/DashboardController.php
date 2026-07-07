<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('instructor.dashboard', [
            'stats' => [
                'courses' => Course::where('instructor_id', auth()->id())->count(),
                'published' => Course::where('instructor_id', auth()->id())->where('is_published', true)->count(),
                'pending' => Course::where('instructor_id', auth()->id())->where('approval_status', 'pending')->count(),
            ],
            'recentCourses' => Course::where('instructor_id', auth()->id())->latest()->take(5)->get(),
        ]);
    }
}
