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
     * Daftar semua quiz milik course tertentu.
     */
    public function index(Course $course): View
    {
        $this->authorize('update', $course);

        $quizzes = Quiz::query()
            ->with(['course', 'creator'])
            ->withCount('questions')
            ->where('course_id', $course->id)
            ->when(request('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('instructor.quizzes.index', compact('course', 'quizzes'));
    }

    /**
     * Form buat quiz baru di dalam course tertentu.
     */
    public function create(Course $course): View
    {
        $this->authorize('update', $course);

        return view('instructor.quizzes.create', compact('course'));
    }

    /**
     * Simpan quiz baru dengan status draft.
     */
    public function store(QuizRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $course->quizzes()->create([
            ...$request->validated(),
            'created_by' => auth()->id(),
            'status'     => Quiz::STATUS_DRAFT,
            'is_published' => false,
        ]);

        return redirect()
            ->route('instructor.courses.quizzes.index', $course)
            ->with('success', 'Quiz berhasil dibuat. Silakan tambahkan soal-soal terlebih dahulu.');
    }

    /**
     * Detail quiz — daftar soal & tombol aksi (edit, submit for review, dll.)
     */
    public function show(Course $course, Quiz $quiz): View
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);

        $quiz->load(['questions.options']);

        return view('instructor.quizzes.show', compact('course', 'quiz'));
    }

    /**
     * Form edit quiz — bisa diedit kapanpun (bahkan jika sudah published).
     */
    public function edit(Course $course, Quiz $quiz): View
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);

        return view('instructor.quizzes.edit', compact('course', 'quiz'));
    }

    /**
     * Update data quiz.
     * Jika quiz sudah published, status berubah ke draft agar admin perlu publish ulang.
     */
    public function update(QuizRequest $request, Course $course, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);

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
            ->route('instructor.courses.quizzes.show', [$course, $quiz])
            ->with('success', $message);
    }

    /**
     * Hapus quiz (hanya jika belum ada attempt dari student).
     */
    public function destroy(Course $course, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);

        if ($quiz->attempts()->exists()) {
            return redirect()
                ->route('instructor.courses.quizzes.index', $course)
                ->with('error', 'Tidak bisa menghapus quiz yang sudah pernah dikerjakan student.');
        }

        $quiz->delete();

        return redirect()
            ->route('instructor.courses.quizzes.index', $course)
            ->with('success', 'Quiz berhasil dihapus.');
    }

    /**
     * Ubah status quiz dari draft/rejected ke pending_review.
     * Quiz akan menunggu review dari Admin.
     */
    public function submitForReview(Course $course, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);

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
            ->route('instructor.courses.quizzes.show', [$course, $quiz])
            ->with('success', 'Quiz berhasil dikirim untuk review. Admin akan segera mereviewnya.');
    }
}
