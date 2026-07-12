<x-app-layout title="Quiz: {{ $quiz->title }}">
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
                <i class="bi bi-arrow-left"></i> Back to Quizzes
            </a>
        </div>

        {{-- Quiz Info Card --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4>{{ $quiz->title }}</h4>
                        <p class="text-muted mb-1">
                            <i class="bi bi-book"></i> Course: <strong>{{ $quiz->course?->title ?? '-' }}</strong>
                        </p>
                        @if($quiz->description)
                            <p class="text-muted mb-1">{{ $quiz->description }}</p>
                        @endif
                        <div class="mt-2">
                            <span class="badge bg-warning text-dark">Passing Score: {{ $quiz->passing_score }}%</span>
                            @if($quiz->time_limit_minutes)
                                <span class="badge bg-info">Time Limit: {{ $quiz->time_limit_minutes }} min</span>
                            @endif
                            <span class="badge bg-{{ $quiz->is_published ? 'success' : 'secondary' }}">
                                {{ $quiz->is_published ? 'Published' : 'Draft' }}
                            </span>
                            <span class="badge bg-primary">{{ $quiz->questions->count() }} Questions</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil-square"></i> Edit Quiz
                    </a>
                </div>
            </div>
        </div>

        {{-- Questions Section --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Questions</h5>
            <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Question
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

                            {{-- Daftar opsi jawaban --}}
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
                                            <span class="badge bg-success ms-2">Correct</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-flex gap-1 ms-3">
                            <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this question and its options?')" title="Delete">
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
                    <p>No questions yet. Click <strong>"Add Question"</strong> to start adding questions.</p>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
