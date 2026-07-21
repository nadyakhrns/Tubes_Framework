<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'courses'          => Course::count(),
                'approved_courses' => Course::where('approval_status', 'approved')->count(),
                'pending_courses'  => Course::where('approval_status', 'pending')->count(),
                'categories'       => Category::count(),
                'instructors'      => User::where('role', User::ROLE_INSTRUCTOR)->count(),
                'quizzes_pending'  => Quiz::where('status', Quiz::STATUS_PENDING_REVIEW)->count(),
            ],
            'recentCourses'  => Course::with(['category', 'instructor'])->latest()->take(5)->get(),
            'pendingQuizzes' => Quiz::with(['course', 'creator'])
                ->where('status', Quiz::STATUS_PENDING_REVIEW)
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
