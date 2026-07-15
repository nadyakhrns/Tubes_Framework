<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\LessonProgressRequest;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LearningController extends Controller
{
    public function show(Enrollment $enrollment, Lesson $lesson): View|RedirectResponse
    {
        $this->authorize('view', $enrollment);
        abort_unless($enrollment->course_id === $lesson->course_id, 404);
        $this->authorize('view', $lesson);

        $course = $enrollment->course()->with(['sections' => fn ($query) => $query->with('lessons')->orderBy('order'), 'lessons', 'quizzes' => fn ($q) => $q->where('is_published', true)])->firstOrFail();

        $lessonProgress = LessonProgress::firstOrCreate([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
        ], [
            'last_accessed_at' => now(),
        ]);

        $lessonProgress->update(['last_accessed_at' => now()]);

        return view('student.learning.show', [
            'course' => $course,
            'enrollment' => $enrollment,
            'lesson' => $lesson,
            'lessonProgress' => $lessonProgress,
            'sections' => $course->sections,
        ]);
    }

    public function complete(LessonProgressRequest $request, Enrollment $enrollment, Lesson $lesson): RedirectResponse
    {
        $this->authorize('complete', $enrollment);
        abort_unless($enrollment->course_id === $lesson->course_id, 404);

        $progress = LessonProgress::firstOrCreate([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
        ]);

        $progress->update([
            'completed_at' => now(),
            'last_accessed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Lesson marked as completed.');
    }
}
