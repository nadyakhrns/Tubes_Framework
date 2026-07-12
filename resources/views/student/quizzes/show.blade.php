<x-app-layout title="Quiz: {{ $quiz->title }}">
    <style>
        /* Custom styles for interactive quiz options */
        .quiz-option-label {
            display: block;
            cursor: pointer;
            margin-bottom: 0;
        }
        .quiz-option-input {
            display: none; /* Sembunyikan radio button asli */
        }
        .quiz-option-card {
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            transition: all 0.2s ease-in-out;
            background-color: #ffffff;
        }
        .quiz-option-card:hover {
            border-color: #babbbc;
            background-color: #f8f9fa;
        }
        /* State saat opsi dipilih */
        .quiz-option-input:checked + .quiz-option-card {
            border-color: #0d6efd; /* Primary color Bootstrap */
            background-color: #f0f4ff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .quiz-option-input:checked + .quiz-option-card .option-marker {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .option-marker {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
            font-weight: bold;
            margin-right: 1rem;
            transition: all 0.2s ease-in-out;
        }
    </style>

    <div class="container py-5 max-w-4xl">
        <x-flash-message />

        {{-- Header Kuis --}}
        <div class="card shadow-sm border-0 mb-4 rounded-4">
            <div class="card-body p-4 text-center">
                <span class="badge bg-primary mb-2 px-3 py-2 rounded-pill">
                    <i class="bi bi-book me-1"></i> {{ $enrollment->course->title }}
                </span>
                <h3 class="fw-bold mb-3">{{ $quiz->title }}</h3>
                <p class="text-muted mb-0 fs-5">{{ $quiz->description }}</p>
                
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <div class="border rounded-3 px-4 py-2 bg-light">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Total Soal</small>
                        <span class="fs-4 fw-bold">{{ $quiz->questions->count() }}</span>
                    </div>
                    <div class="border rounded-3 px-4 py-2 bg-light">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Passing Score</small>
                        <span class="fs-4 fw-bold">{{ $quiz->passing_score }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Pengerjaan Kuis --}}
        <form method="POST" action="{{ route('student.quiz.submit', [$enrollment, $quiz]) }}">
            @csrf
            
            @foreach($quiz->questions as $index => $question)
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body p-4">
                        {{-- Pertanyaan --}}
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <span class="badge bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 1rem;">
                                    {{ $index + 1 }}
                                </span>
                            </div>
                            <div>
                                <h5 class="fw-semibold lh-base mb-0" style="margin-top: 5px;">
                                    {{ $question->question }}
                                </h5>
                                <small class="text-muted">{{ $question->points }} Points</small>
                            </div>
                        </div>

                        {{-- Pilihan Ganda (Interactive Cards) --}}
                        <div class="row g-3 ps-md-5">
                            @foreach($question->options as $optIndex => $option)
                                <div class="col-12">
                                    <label class="quiz-option-label" for="q{{ $question->id }}o{{ $option->id }}">
                                        {{-- Radio input disembunyikan via CSS --}}
                                        <input class="quiz-option-input" 
                                               type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               id="q{{ $question->id }}o{{ $option->id }}" 
                                               value="{{ $option->id }}"
                                               required>
                                        
                                        {{-- Visual Card --}}
                                        <div class="quiz-option-card d-flex align-items-center">
                                            <span class="option-marker">{{ chr(65 + $optIndex) }}</span>
                                            <span class="fs-6">{{ $option->option_text }}</span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Tombol Submit --}}
            <div class="text-center mt-5 mb-4">
                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm" onclick="return confirm('Apakah kamu yakin ingin mengumpulkan kuis ini? Jawaban tidak dapat diubah setelah disubmit.')">
                    <i class="bi bi-send-check-fill me-2"></i> Submit Kuis Sekarang
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
