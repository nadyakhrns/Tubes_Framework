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
        // 1. Otorisasi dan Pengecekan Akses
        $this->authorize('view', $enrollment);
        abort_unless($enrollment->course_id === $quiz->course_id, 404);
        $this->authorize('submit', $quiz);

        // 2. Mencegah mahasiswa submit kuis yang sama lebih dari satu kali
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->first();
        if ($existingAttempt) {
            return redirect()->route('student.quiz.result', [$enrollment, $quiz])->with('error', 'You already attempted this quiz.');
        }

        // 3. Mengambil semua soal kuis yang aktif beserta opsi jawabannya
        $questions = $quiz->questions()->with('options')->where('is_active', true)->get();
        
        // Inisialisasi variabel untuk perhitungan skor
        $correctAnswers = 0; // Menyimpan jumlah jawaban yang benar
        $answers = [];       // Array sementara untuk menampung riwayat jawaban user

        // 4. Looping Jawaban dari Student
        // Kita iterasi satu per satu soal yang ada di database untuk mencocokkan dengan jawaban user
        foreach ($questions as $question) {
            // Ambil ID opsi yang dipilih user dari form request. 
            // Name di form HTML biasanya berupa: name="answers[{{ $question->id }}]"
            $selectedOptionId = $request->input('answers.'.$question->id);
            $isCorrect = false; // Default: anggap salah

            // Jika user menjawab (tidak dikosongkan)
            if ($selectedOptionId) {
                // 5. Cek ke tabel options apakah jawaban tersebut is_correct
                $option = $question->options()->find($selectedOptionId);
                
                // Pastikan opsi ditemukan dan nilai is_correct di database adalah true
                if ($option && $option->is_correct) {
                    $correctAnswers++; // Tambah counter jawaban benar
                    $isCorrect = true; // Flag bahwa jawaban ini benar
                }
            }

            // Simpan data jawaban ini ke dalam array sementara
            // Kita kumpulkan dulu untuk disimpan batch / iterasi di bawah nanti
            $answers[] = [
                'question_id' => $question->id,
                'option_id' => $selectedOptionId,
                'is_correct' => $isCorrect,
            ];
        }

        // 6. Hitung Kalkulasi Skor Menjadi Skala 100
        // Rumus: (Jumlah Benar / Total Soal) * 100
        // max(..., 1) digunakan untuk mencegah error division by zero jika kebetulan soal kosong
        $score = (int) round(($correctAnswers / max($questions->count(), 1)) * 100);

        // 7. Simpan Hasil Akhir ke quiz_attempts
        // Disini kita tentukan status kelulusan (passed/failed) berdasarkan passing_score
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'score' => $score, // Skor akhir skala 0-100
            'total_questions' => $questions->count(),
            'correct_answers' => $correctAnswers,
            'passed' => $score >= $quiz->passing_score, // Boolean: True jika skor >= nilai KKM kuis
            'submitted_at' => now(), // Waktu kuis disubmit
        ]);

        // 8. Simpan Tiap Jawaban ke quiz_answers
        // Looping array $answers yang sudah kita buat sebelumnya, lalu simpan ke database
        // dan hubungkan dengan ID $attempt yang baru saja terbuat
        foreach ($answers as $answer) {
            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $answer['question_id'],
                'option_id' => $answer['option_id'],
                'is_correct' => $answer['is_correct'],
            ]);
        }

        // Redirect ke halaman hasil kuis
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
