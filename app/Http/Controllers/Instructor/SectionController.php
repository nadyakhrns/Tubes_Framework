<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\SectionRequest;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(Course $course): View
    {
        $this->authorize('update', $course);

        $sections = $course->sections()->latest()->paginate(10);

        return view('instructor.sections.index', compact('course', 'sections'));
    }

    public function create(Course $course): View
    {
        $this->authorize('update', $course);

        return view('instructor.sections.create', compact('course'));
    }

    public function store(SectionRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $course->sections()->create($request->validated());

        return redirect()->route('instructor.courses.sections.index', $course)->with('success', 'Section created successfully.');
    }

    public function edit(Course $course, Section $section): View
    {
        $this->authorize('update', $course);

        return view('instructor.sections.edit', compact('course', 'section'));
    }

    public function update(SectionRequest $request, Course $course, Section $section): RedirectResponse
    {
        $this->authorize('update', $course);

        $section->update($request->validated());

        return redirect()->route('instructor.courses.sections.index', $course)->with('success', 'Section updated successfully.');
    }

    public function destroy(Course $course, Section $section): RedirectResponse
    {
        $this->authorize('update', $course);
        $section->delete();

        return redirect()->route('instructor.courses.sections.index', $course)->with('success', 'Section deleted successfully.');
    }
}
