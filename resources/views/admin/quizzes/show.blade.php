<x-app-layout title="Review Quiz: {{ $quiz->title }}">
    <div class="container-fluid py-4">
        <x-flash-message />

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Quiz
            </a>
        </div>

        {{-- Quiz Info Card --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1">{{ $quiz->title }}</h4>
                        <p class="text-muted mb-1">
                            <i class="bi bi-book"></i> Course: <strong>{{ $quiz->course?->title ?? '-' }}</strong>
                        </p>
                        <p class="text-muted mb-1">
                            <i class="bi bi-person"></i> Instructor: <strong>{{ $quiz->creator?->name ?? 'N/A' }}</strong>
                        </p>
                        @if($quiz->description)
                            <p class="text-muted mb-2">{{ $quiz->description }}</p>
                        @endif
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <span class="badge bg-warning text-dark">Passing Score: {{ $quiz->passing_score }}%</span>
                            @if($quiz->time_limit_minutes)
                                <span class="badge bg-info">Waktu: {{ $quiz->time_limit_minutes }} menit</span>
                            @endif
                            <span class="badge bg-{{ $quiz->statusColor() }} fs-6">{{ $quiz->statusLabel() }}</span>
                            <span class="badge bg-secondary">{{ $quiz->questions->count() }} Soal</span>
                        </div>
                    </div>

                    {{-- Admin Action Buttons --}}
                    <div class="d-flex gap-2 flex-wrap align-items-start">
                        @if($quiz->isPendingReview() || $quiz->isDraft())
                            {{-- PUBLISH --}}
                            <form action="{{ route('admin.quizzes.publish', $quiz) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Publish quiz ini? Student akan bisa mengaksesnya.')">
                                    <i class="bi bi-check-circle-fill"></i> Publish
                                </button>
                            </form>

                            {{-- REJECT (buka modal) --}}
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle-fill"></i> Tolak
                            </button>
                        @endif

                        @if($quiz->isPublished())
                            {{-- UNPUBLISH --}}
                            <form action="{{ route('admin.quizzes.unpublish', $quiz) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning"
                                        onclick="return confirm('Unpublish quiz ini? Student tidak akan bisa mengaksesnya.')">
                                    <i class="bi bi-pause-circle"></i> Unpublish
                                </button>
                            </form>
                        @endif

                        @if($quiz->isRejected())
                            {{-- RE-PUBLISH jika sudah diperbaiki --}}
                            <form action="{{ route('admin.quizzes.publish', $quiz) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Publish quiz ini setelah review?')">
                                    <i class="bi bi-check-circle-fill"></i> Publish
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Catatan penolakan jika ada --}}
                @if($quiz->isRejected() && $quiz->rejection_note)
                    <div class="alert alert-danger mt-3 mb-0 d-flex align-items-start gap-2">
                        <i class="bi bi-x-octagon-fill fs-5 mt-1"></i>
                        <div>
                            <strong>Catatan Penolakan:</strong> {{ $quiz->rejection_note }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Questions Section (Read-only preview) --}}
        <h5 class="mb-3"><i class="bi bi-list-ol"></i> Daftar Soal (Preview)</h5>

        @forelse($quiz->questions as $index => $question)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
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
            </div>
        @empty
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-question-circle fs-1 d-block mb-2"></i>
                    <p>Quiz ini belum memiliki soal.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Modal Reject --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.quizzes.reject', $quiz) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">
                            <i class="bi bi-x-circle-fill text-danger"></i> Tolak Quiz
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Berikan catatan alasan penolakan untuk <strong>{{ $quiz->title }}</strong>.
                            Instructor akan melihat catatan ini di dashboard mereka.</p>
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="rejection_note" class="form-control" rows="4" required
                                      placeholder="Contoh: Soal nomor 3 tidak memiliki jawaban yang jelas..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle"></i> Tolak Quiz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
