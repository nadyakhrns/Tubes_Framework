<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\LessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(Course $course): View
    {
        $this->authorize('update', $course);

        $lessons = $course->lessons()->with('section')->latest()->paginate(10);

        return view('instructor.lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course): View
    {
        $this->authorize('update', $course);

        return view('instructor.lessons.create', [
            'course' => $course,
            'sections' => $course->sections()->get(),
        ]);
    }

    public function store(LessonRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $data = $request->validated();
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('lessons', 'public');
        }

        $course->lessons()->create($data);

        return redirect()->route('instructor.courses.lessons.index', $course)->with('success', 'Lesson created successfully.');
    }

    public function edit(Course $course, Lesson $lesson): View
    {
        $this->authorize('update', $course);
        $this->authorize('update', $lesson);

        return view('instructor.lessons.edit', [
            'course' => $course,
            'lesson' => $lesson,
            'sections' => $course->sections()->get(),
        ]);
    }

    public function update(LessonRequest $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $course);
        $this->authorize('update', $lesson);

        $data = $request->validated();
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('lessons', 'public');
        }

        $lesson->update($data);

        return redirect()->route('instructor.courses.lessons.index', $course)->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $course);
        $this->authorize('delete', $lesson);
        $lesson->delete();

        return redirect()->route('instructor.courses.lessons.index', $course)->with('success', 'Lesson deleted successfully.');
    }
}
