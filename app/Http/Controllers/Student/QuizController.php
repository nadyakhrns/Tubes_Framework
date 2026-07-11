<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\QuizAttemptRequest;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function show(Enrollment $enrollment, Quiz $quiz): View|RedirectResponse
    {
        $this->authorize('view', $enrollment);
        abort_unless($enrollment->course_id === $quiz->course_id, 404);
        $this->authorize('view', $quiz);

        $attemptExists = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->exists();
        if ($attemptExists) {
            return redirect()->route('student.quiz.result', [$enrollment, $quiz])->with('error', 'You already attempted this quiz.');
        }

        $quiz->load(['questions' => fn ($query) => $query->with('options')->where('is_active', true)]);

        return view('student.quizzes.show', compact('enrollment', 'quiz'));
    }

    public function submit(QuizAttemptRequest $request, Enrollment $enrollment, Quiz $quiz): RedirectResponse
    {
        $this->authorize('view', $enrollment);
        abort_unless($enrollment->course_id === $quiz->course_id, 404);
        $this->authorize('submit', $quiz);

        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->first();
        if ($existingAttempt) {
            return redirect()->route('student.quiz.result', [$enrollment, $quiz])->with('error', 'You already attempted this quiz.');
        }

        $questions = $quiz->questions()->with('options')->where('is_active', true)->get();
        $correctAnswers = 0;
        $answers = [];

        foreach ($questions as $question) {
            $selectedOptionId = $request->input('answers.'.$question->id);
            $isCorrect = false;

            if ($selectedOptionId) {
                $option = $question->options()->find($selectedOptionId);
                if ($option && $option->is_correct) {
                    $correctAnswers++;
                    $isCorrect = true;
                }
            }

            $answers[] = [
                'question_id' => $question->id,
                'option_id' => $selectedOptionId,
                'is_correct' => $isCorrect,
            ];
        }

        $score = (int) round(($correctAnswers / max($questions->count(), 1)) * 100);

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'score' => $score,
            'total_questions' => $questions->count(),
            'correct_answers' => $correctAnswers,
            'passed' => $score >= $quiz->passing_score,
            'submitted_at' => now(),
        ]);

        foreach ($answers as $answer) {
            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $answer['question_id'],
                'option_id' => $answer['option_id'],
                'is_correct' => $answer['is_correct'],
            ]);
        }

        return redirect()->route('student.quiz.result', [$enrollment, $quiz])->with('success', 'Quiz submitted successfully.');
    }

    public function result(Enrollment $enrollment, Quiz $quiz): View
    {
        $this->authorize('view', $enrollment);
        abort_unless($enrollment->course_id === $quiz->course_id, 404);
        $this->authorize('result', $quiz);

        $attempt = QuizAttempt::with('answers.question')->where('quiz_id', $quiz->id)->where('user_id', auth()->id())->latest()->firstOrFail();

        return view('student.quizzes.result', compact('enrollment', 'quiz', 'attempt'));
    }
}
