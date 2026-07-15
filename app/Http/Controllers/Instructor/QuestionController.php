<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\QuestionRequest;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /** Form tambah pertanyaan baru untuk quiz tertentu. */
    public function create(Quiz $quiz): View
    {
        $this->authorize('update', $quiz->course);

        return view('instructor.quizzes.questions.create', compact('quiz'));
    }

    /**
     * Simpan Question + semua Options dalam 1 transaction.
     */
    public function store(QuestionRequest $request, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);

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
            ->route('instructor.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    /** Form edit pertanyaan beserta opsi-opsinya. */
    public function edit(Quiz $quiz, Question $question): View
    {
        $this->authorize('update', $quiz->course);
        abort_unless($question->quiz_id === $quiz->id, 404);

        $question->load('options');

        return view('instructor.quizzes.questions.edit', compact('quiz', 'question'));
    }

    /**
     * Update Question + semua Options (hapus lama, buat ulang).
     */
    public function update(QuestionRequest $request, Quiz $quiz, Question $question): RedirectResponse
    {
        $this->authorize('update', $quiz->course);
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
            ->route('instructor.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /** Hapus pertanyaan (options ikut terhapus via cascadeOnDelete). */
    public function destroy(Quiz $quiz, Question $question): RedirectResponse
    {
        $this->authorize('update', $quiz->course);
        abort_unless($question->quiz_id === $quiz->id, 404);

        $question->delete();

        return redirect()
            ->route('instructor.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil dihapus.');
    }
}
