<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuizRequest;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Daftar semua kuis, lengkap dengan relasi course dan jumlah questions.
     */
    public function index(): View
    {
        $quizzes = Quiz::query()
            ->with('course')
            ->withCount('questions')
            ->when(request('search'), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when(request('course_id'), fn ($query, $courseId) => $query->where('course_id', $courseId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $courses = Course::query()->orderBy('title')->get();

        return view('admin.quizzes.index', compact('quizzes', 'courses'));
    }

    public function create(): View
    {
        $courses = Course::query()->orderBy('title')->get();

        return view('admin.quizzes.create', compact('courses'));
    }

    public function store(QuizRequest $request): RedirectResponse
    {
        Quiz::create($request->validated());

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created successfully.');
    }

    /**
     * Halaman detail quiz: menampilkan daftar pertanyaan beserta opsinya.
     * Digunakan sebagai "pusat" untuk mengelola pertanyaan dalam 1 quiz.
     */
    public function show(Quiz $quiz): View
    {
        $quiz->load(['course', 'questions.options']);

        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz): View
    {
        $courses = Course::query()->orderBy('title')->get();

        return view('admin.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(QuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $quiz->update($request->validated());

        return redirect()->route('admin.quizzes.show', $quiz)->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        // Cegah penghapusan quiz yang sudah pernah di-attempt oleh student
        if ($quiz->attempts()->exists()) {
            return redirect()->route('admin.quizzes.index')
                ->with('error', 'Cannot delete quiz that has student attempts.');
        }

        $quiz->delete();

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz deleted successfully.');
    }
}
