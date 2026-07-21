<x-app-layout title="Quiz: {{ $quiz->title }}">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="mb-3">
            <a href="{{ route('instructor.quizzes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Quiz
            </a>
        </div>

        {{-- Notifikasi jika quiz ditolak --}}
        @if($quiz->isRejected() && $quiz->rejection_note)
            <div class="alert alert-danger d-flex align-items-start gap-2 mb-3">
                <i class="bi bi-x-octagon-fill fs-5 mt-1"></i>
                <div>
                    <strong>Quiz Ditolak oleh Admin.</strong><br>
                    Alasan: <em>{{ $quiz->rejection_note }}</em><br>
                    <small class="text-muted">Silakan perbaiki quiz lalu kirim ulang untuk review.</small>
                </div>
            </div>
        @endif

        {{-- Quiz Info Card --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1">{{ $quiz->title }}</h4>
                        <p class="text-muted mb-1">
                            <i class="bi bi-book"></i> Course: <strong>{{ $quiz->course?->title ?? '-' }}</strong>
                        </p>
                        @if($quiz->description)
                            <p class="text-muted mb-2">{{ $quiz->description }}</p>
                        @endif
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-warning text-dark">Passing Score: {{ $quiz->passing_score }}%</span>
                            @if($quiz->time_limit_minutes)
                                <span class="badge bg-info">Waktu: {{ $quiz->time_limit_minutes }} menit</span>
                            @endif
                            <span class="badge bg-{{ $quiz->statusColor() }} fs-6">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i>
                                {{ $quiz->statusLabel() }}
                            </span>
                            <span class="badge bg-secondary">{{ $quiz->questions->count() }} Soal</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('instructor.quizzes.edit', $quiz) }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil-square"></i> Edit Quiz
                        </a>

                        @if($quiz->isDraft() || $quiz->isRejected())
                            <form action="{{ route('instructor.quizzes.submit-review', $quiz) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Kirim quiz ini ke Admin untuk direview?')">
                                    <i class="bi bi-send-check"></i> Kirim untuk Review
                                </button>
                            </form>
                        @elseif($quiz->isPendingReview())
                            <span class="btn btn-warning btn-sm disabled">
                                <i class="bi bi-hourglass-split"></i> Menunggu Review Admin
                            </span>
                        @elseif($quiz->isPublished())
                            <span class="btn btn-success btn-sm disabled">
                                <i class="bi bi-check-circle"></i> Sudah Published
                            </span>
                        @endif

                        @unless($quiz->attempts()->exists())
                            <form action="{{ route('instructor.quizzes.destroy', $quiz) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Hapus quiz ini beserta semua soalnya?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            </div>
        </div>

        {{-- Questions Section --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="bi bi-list-ol"></i> Daftar Soal</h5>
            <a href="{{ route('instructor.quizzes.questions.create', $quiz) }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Soal
            </a>
        </div>

        @forelse($quiz->questions as $index => $question)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6>
                                <span class="badge bg-dark me-2">{{ $index + 1 }}</span>
                                {{ $question->question }}
                                <small class="text-muted">({{ $question->points }} pts)</small>
                            </h6>

                            <div class="ms-4 mt-2">
                                @foreach($question->options as $option)
                                    <div class="d-flex align-items-center mb-1">
                                        @if($option->is_correct)
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        @else
                                            <i class="bi bi-circle text-muted me-2"></i>
                                        @endif
                                        <span class="{{ $option->is_correct ? 'fw-semibold text-success' : '' }}">
                                            {{ $option->option_text }}
                                        </span>
                                        @if($option->is_correct)
                                            <span class="badge bg-success ms-2">Benar</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex gap-1 ms-3">
                            <a href="{{ route('instructor.quizzes.questions.edit', [$quiz, $question]) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit Soal">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('instructor.quizzes.questions.destroy', [$quiz, $question]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus soal ini?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-question-circle fs-1 d-block mb-2"></i>
                    <p>Belum ada soal. Klik <strong>"Tambah Soal"</strong> untuk mulai menambahkan soal.</p>
                    <p class="small">Anda perlu menambahkan minimal 1 soal sebelum bisa mengirim quiz untuk review.</p>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
