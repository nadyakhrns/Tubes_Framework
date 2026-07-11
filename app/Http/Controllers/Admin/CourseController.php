<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->with(['category', 'instructor'])
            ->when(request('search'), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('admin.courses.create', [
            'categories' => Category::query()->where('is_active', true)->get(),
            'instructors' => User::where('role', User::ROLE_INSTRUCTOR)->get(),
        ]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Course::create([
            ...$data,
            'slug' => Str::slug($request->input('title')),
            'instructor_id' => $request->input('instructor_id'),
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', [
            'course' => $course,
            'categories' => Category::query()->where('is_active', true)->get(),
            'instructors' => User::where('role', User::ROLE_INSTRUCTOR)->get(),
        ]);
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update([
            ...$data,
            'slug' => Str::slug($request->input('title')),
            'instructor_id' => $request->input('instructor_id'),
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }

    public function approve(Course $course): RedirectResponse
    {
        $course->update(['approval_status' => 'approved']);

        return redirect()->back()->with('success', 'Course approved successfully.');
    }

    public function reject(Course $course): RedirectResponse
    {
        $course->update(['approval_status' => 'rejected']);

        return redirect()->back()->with('success', 'Course rejected successfully.');
    }

    public function publish(Course $course): RedirectResponse
    {
        $course->update(['is_published' => true]);

        return redirect()->back()->with('success', 'Course published successfully.');
    }

    public function unpublish(Course $course): RedirectResponse
    {
        $course->update(['is_published' => false]);

        return redirect()->back()->with('success', 'Course unpublished successfully.');
    }
}
