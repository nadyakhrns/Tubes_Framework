<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $instructorId = auth()->id();

        // ID course milik instructor ini
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

        // Notifikasi: quiz yang baru saja di-publish oleh admin (belum ditandai dibaca)
        $publishedQuizzes = Quiz::whereIn('course_id', $courseIds)
            ->where('status', Quiz::STATUS_PUBLISHED)
            ->where('is_published', true)
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Notifikasi: quiz yang ditolak admin
        $rejectedQuizzes = Quiz::whereIn('course_id', $courseIds)
            ->where('status', Quiz::STATUS_REJECTED)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('instructor.dashboard', [
            'stats' => [
                'courses'            => Course::where('instructor_id', $instructorId)->count(),
                'published'          => Course::where('instructor_id', $instructorId)->where('is_published', true)->count(),
                'pending'            => Course::where('instructor_id', $instructorId)->where('approval_status', 'pending')->count(),
                'quizzes_total'      => Quiz::whereIn('course_id', $courseIds)->count(),
                'quizzes_pending'    => Quiz::whereIn('course_id', $courseIds)->where('status', Quiz::STATUS_PENDING_REVIEW)->count(),
                'quizzes_published'  => Quiz::whereIn('course_id', $courseIds)->where('status', Quiz::STATUS_PUBLISHED)->count(),
                'quizzes_rejected'   => Quiz::whereIn('course_id', $courseIds)->where('status', Quiz::STATUS_REJECTED)->count(),
            ],
            'recentCourses'     => Course::where('instructor_id', $instructorId)->latest()->take(5)->get(),
            'publishedQuizzes'  => $publishedQuizzes,
            'rejectedQuizzes'   => $rejectedQuizzes,
        ]);
    }
}
