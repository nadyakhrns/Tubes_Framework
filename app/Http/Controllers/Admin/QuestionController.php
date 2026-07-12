<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuestionRequest;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Form tambah pertanyaan baru untuk quiz tertentu.
     */
    public function create(Quiz $quiz): View
    {
        return view('admin.quizzes.questions.create', compact('quiz'));
    }

    /**
     * Menyimpan Question + semua Options sekaligus dalam 1 request.
     *
     * LOGIKA UTAMA:
     * 1. Buat record Question terlebih dahulu.
     * 2. Loop melalui array options[] dari form.
     * 3. Untuk setiap option, buat record Option dan tentukan is_correct.
     * 4. Semua dibungkus dalam DB::transaction agar atomik (all or nothing).
     */
    public function store(QuestionRequest $request, Quiz $quiz): RedirectResponse
    {
        DB::transaction(function () use ($request, $quiz) {
            // 1. Buat Question
            $question = $quiz->questions()->create([
                'question' => $request->input('question'),
                'points' => $request->input('points', 1),
            ]);

            // 2. Loop dan buat Options (biasanya 4 pilihan ganda)
            foreach ($request->input('options') as $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['option_text'],
                    // is_correct = true hanya jika checkbox/radio di-check
                    'is_correct' => !empty($optionData['is_correct']),
                ]);
            }
        });

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Question added successfully.');
    }

    /**
     * Form edit pertanyaan beserta opsi-opsinya.
     */
    public function edit(Quiz $quiz, Question $question): View
    {
        $question->load('options');

        return view('admin.quizzes.questions.edit', compact('quiz', 'question'));
    }

    /**
     * Update Question + semua Options sekaligus.
     *
     * STRATEGI: Hapus semua options lama, lalu buat ulang.
     * Pendekatan ini lebih sederhana daripada tracking mana yang berubah,
     * dan tetap aman karena dibungkus transaction + cascadeOnDelete di migration.
     */
    public function update(QuestionRequest $request, Quiz $quiz, Question $question): RedirectResponse
    {
        DB::transaction(function () use ($request, $question) {
            // 1. Update data Question
            $question->update([
                'question' => $request->input('question'),
                'points' => $request->input('points', 1),
            ]);

            // 2. Hapus semua options lama dan buat ulang
            $question->options()->delete();

            foreach ($request->input('options') as $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['option_text'],
                    'is_correct' => !empty($optionData['is_correct']),
                ]);
            }
        });

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Hapus pertanyaan (options ikut terhapus via cascadeOnDelete).
     */
    public function destroy(Quiz $quiz, Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Question deleted successfully.');
    }
}
