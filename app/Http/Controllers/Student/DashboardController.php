<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $enrollments = Enrollment::query()
            ->with(['course' => fn ($query) => $query->with(['category', 'instructor'])])
            ->where('user_id', $user->id)
            ->latest('enrolled_at')
            ->take(5)
            ->get();

        $stats = [
            'enrolled_courses' => Enrollment::where('user_id', $user->id)->count(),
            'overall_progress' => $this->calculateOverallProgress($user->id),
        ];

        return view('student.dashboard', [
            'stats' => $stats,
            'latestEnrollments' => $enrollments,
        ]);
    }

    private function calculateOverallProgress(int $userId): int
    {
        $enrollments = Enrollment::query()
            ->where('user_id', $userId)
            ->with(['course.lessons.progresses' => fn ($query) => $query->where('user_id', $userId)])
            ->get();

        if ($enrollments->isEmpty()) {
            return 0;
        }

        $completed = 0;
        $total = 0;

        foreach ($enrollments as $enrollment) {
            $lessons = $enrollment->course?->lessons ?? collect();

            if ($lessons->isEmpty()) {
                continue;
            }

            $total += $lessons->count();
            $completed += $lessons->filter(fn ($lesson) => $lesson->progresses->contains(fn ($progress) => $progress->completed_at !== null))->count();
        }

        if ($total === 0) {
            return 0;
        }

        return (int) round(($completed / $total) * 100);
    }
}
