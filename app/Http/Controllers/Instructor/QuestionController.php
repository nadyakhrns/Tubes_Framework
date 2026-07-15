<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\QuestionRequest;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /** Form tambah pertanyaan baru untuk quiz tertentu. */
    public function create(Course $course, Quiz $quiz): View
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);

        return view('instructor.quizzes.questions.create', compact('course', 'quiz'));
    }

    /**
     * Simpan Question + semua Options dalam 1 transaction.
     */
    public function store(QuestionRequest $request, Course $course, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);

        DB::transaction(function () use ($request, $quiz) {
            $question = $quiz->questions()->create([
                'question' => $request->input('question'),
                'points'   => $request->input('points', 1),
            ]);

            foreach ($request->input('options') as $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['option_text'],
                    'is_correct'  => !empty($optionData['is_correct']),
                ]);
            }
        });

        return redirect()
            ->route('instructor.courses.quizzes.show', [$course, $quiz])
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    /** Form edit pertanyaan beserta opsi-opsinya. */
    public function edit(Course $course, Quiz $quiz, Question $question): View
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);
        abort_unless($question->quiz_id === $quiz->id, 404);

        $question->load('options');

        return view('instructor.quizzes.questions.edit', compact('course', 'quiz', 'question'));
    }

    /**
     * Update Question + semua Options (hapus lama, buat ulang).
     */
    public function update(QuestionRequest $request, Course $course, Quiz $quiz, Question $question): RedirectResponse
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);
        abort_unless($question->quiz_id === $quiz->id, 404);

        DB::transaction(function () use ($request, $question) {
            $question->update([
                'question' => $request->input('question'),
                'points'   => $request->input('points', 1),
            ]);

            $question->options()->delete();

            foreach ($request->input('options') as $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['option_text'],
                    'is_correct'  => !empty($optionData['is_correct']),
                ]);
            }
        });

        return redirect()
            ->route('instructor.courses.quizzes.show', [$course, $quiz])
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /** Hapus pertanyaan (options ikut terhapus via cascadeOnDelete). */
    public function destroy(Course $course, Quiz $quiz, Question $question): RedirectResponse
    {
        $this->authorize('update', $course);
        abort_unless($quiz->course_id === $course->id, 404);
        abort_unless($question->quiz_id === $quiz->id, 404);

        $question->delete();

        return redirect()
            ->route('instructor.courses.quizzes.show', [$course, $quiz])
            ->with('success', 'Soal berhasil dihapus.');
    }
}
