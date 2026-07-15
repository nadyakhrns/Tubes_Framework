<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\QuizRequest;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Daftar semua quiz milik instructor yang sedang login.
     */
    public function index(): View
    {
        $quizzes = Quiz::query()
            ->with(['course', 'creator'])
            ->withCount('questions')
            ->whereHas('course', fn ($q) => $q->where('instructor_id', auth()->id()))
            ->when(request('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
            ->when(request('course_id'), fn ($q, $c) => $q->where('course_id', $c))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $courses = Course::where('instructor_id', auth()->id())->get();

        return view('instructor.quizzes.index', compact('quizzes', 'courses'));
    }

    /**
     * Form buat quiz baru.
     */
    public function create(): View
    {
        $courses = Course::where('instructor_id', auth()->id())->get();
        return view('instructor.quizzes.create', compact('courses'));
    }

    /**
     * Simpan quiz baru dengan status draft.
     */
    public function store(QuizRequest $request): RedirectResponse
    {
        $course = Course::findOrFail($request->course_id);
        $this->authorize('update', $course);

        Quiz::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
            'status'     => Quiz::STATUS_DRAFT,
            'is_published' => false,
        ]);

        return redirect()
            ->route('instructor.quizzes.index')
            ->with('success', 'Quiz berhasil dibuat. Silakan buka detail kuis untuk menambahkan soal-soal terlebih dahulu.');
    }

    /**
     * Detail quiz — daftar soal & tombol aksi (edit, submit for review, dll.)
     */
    public function show(Quiz $quiz): View
    {
        $this->authorize('update', $quiz->course);

        $quiz->load(['questions.options', 'course']);

        return view('instructor.quizzes.show', compact('quiz'));
    }

    /**
     * Form edit quiz — bisa diedit kapanpun (bahkan jika sudah published).
     */
    public function edit(Quiz $quiz): View
    {
        $this->authorize('update', $quiz->course);
        $courses = Course::where('instructor_id', auth()->id())->get();

        return view('instructor.quizzes.edit', compact('quiz', 'courses'));
    }

    /**
     * Update data quiz.
     * Jika quiz sudah published, status berubah ke draft agar admin perlu publish ulang.
     */
    public function update(QuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $newCourse = Course::findOrFail($request->course_id);
        $this->authorize('update', $newCourse); // Pastikan course baru juga milik instructor ini
        $this->authorize('update', $quiz->course); // Pastikan instructor berhak mengubah kuis ini

        $newStatus = $quiz->isPublished() ? Quiz::STATUS_DRAFT : $quiz->status;

        $quiz->update([
            ...$request->validated(),
            'status'       => $newStatus,
            'is_published' => false, // Harus publish ulang jika ada perubahan
            'rejection_note' => null, // Reset catatan penolakan
        ]);

        $message = $quiz->isPublished()
            ? 'Quiz diperbarui. Status dikembalikan ke Draft — perlu direview ulang oleh Admin.'
            : 'Quiz berhasil diperbarui.';

        return redirect()
            ->route('instructor.quizzes.show', $quiz)
            ->with('success', $message);
    }

    /**
     * Hapus quiz (hanya jika belum ada attempt dari student).
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);

        if ($quiz->attempts()->exists()) {
            return redirect()
                ->route('instructor.quizzes.index')
                ->with('error', 'Tidak bisa menghapus quiz yang sudah pernah dikerjakan student.');
        }

        $quiz->delete();

        return redirect()
            ->route('instructor.quizzes.index')
            ->with('success', 'Quiz berhasil dihapus.');
    }

    /**
     * Ubah status quiz dari draft/rejected ke pending_review.
     * Quiz akan menunggu review dari Admin.
     */
    public function submitForReview(Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);

        if ($quiz->questions()->count() === 0) {
            return redirect()
                ->back()
                ->with('error', 'Quiz harus memiliki minimal 1 soal sebelum dikirim untuk review.');
        }

        if ($quiz->isPendingReview()) {
            return redirect()
                ->back()
                ->with('error', 'Quiz sudah dalam status Pending Review.');
        }

        $quiz->update([
            'status'         => Quiz::STATUS_PENDING_REVIEW,
            'rejection_note' => null,
        ]);

        return redirect()
            ->route('instructor.quizzes.show', $quiz)
            ->with('success', 'Quiz berhasil dikirim untuk review. Admin akan segera mereviewnya.');
    }
}
