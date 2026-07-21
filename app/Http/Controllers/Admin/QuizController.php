<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Daftar semua kuis — Admin bisa filter berdasarkan status dan course.
     */
    public function index(): View
    {
        $quizzes = Quiz::query()
            ->with(['course', 'creator'])
            ->withCount('questions')
            ->when(request('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when(request('course_id'), fn ($q, $id) => $q->where('course_id', $id))
            ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $courses = Course::query()->orderBy('title')->get();

        return view('admin.quizzes.index', compact('quizzes', 'courses'));
    }

    /**
     * Preview detail quiz + daftar soal (read-only untuk admin).
     * Tombol Publish / Reject ada di sini.
     */
    public function show(Quiz $quiz): View
    {
        $quiz->load(['course', 'creator', 'questions.options']);

        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Publish quiz: status → published, is_published = true.
     * Kirim notifikasi ke instructor via session flash (ditampilkan di dashboard mereka).
     */
    public function publish(Quiz $quiz): RedirectResponse
    {
        $quiz->update([
            'status'         => Quiz::STATUS_PUBLISHED,
            'is_published'   => true,
            'rejection_note' => null,
        ]);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', "Quiz \"{$quiz->title}\" berhasil dipublish.");
    }

    /**
     * Unpublish quiz: status → draft, is_published = false.
     * Instructor bisa edit dan submit ulang.
     */
    public function unpublish(Quiz $quiz): RedirectResponse
    {
        $quiz->update([
            'status'       => Quiz::STATUS_DRAFT,
            'is_published' => false,
        ]);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', "Quiz \"{$quiz->title}\" berhasil di-unpublish. Instructor dapat mengedit dan mengirimkan ulang.");
    }

    /**
     * Tolak (reject) quiz dengan catatan alasan.
     * Status → rejected. Instructor bisa edit dan submit ulang.
     */
    public function reject(Request $request, Quiz $quiz): RedirectResponse
    {
        $request->validate([
            'rejection_note' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_note.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $quiz->update([
            'status'         => Quiz::STATUS_REJECTED,
            'is_published'   => false,
            'rejection_note' => $request->input('rejection_note'),
        ]);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', "Quiz \"{$quiz->title}\" ditolak. Instructor akan menerima notifikasi.");
    }

    /**
     * Hapus quiz (admin tetap bisa menghapus quiz apapun kondisinya).
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        if ($quiz->attempts()->exists()) {
            return redirect()
                ->route('admin.quizzes.index')
                ->with('error', 'Tidak bisa menghapus quiz yang sudah pernah dikerjakan student.');
        }

        $quiz->delete();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz berhasil dihapus.');
    }
}
