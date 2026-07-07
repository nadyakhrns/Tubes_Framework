<x-app-layout title="Course Details">
    <div class="container-fluid py-4">
        <x-flash-message />

        <a href="{{ route('student.courses.index') }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Courses
        </a>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/'.$course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 260px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h3 class="mb-2">{{ $course->title }}</h3>
                        <p class="text-muted">{{ $course->subtitle }}</p>
                        <p>{{ $course->description }}</p>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark">{{ $course->category?->name }}</span>
                            <span class="badge bg-info text-dark">{{ ucfirst($course->level ?? 'beginner') }}</span>
                        </div>
                        <p class="small mb-2"><strong>Instructor:</strong> {{ $course->instructor?->name }}</p>
                        <p class="small mb-3"><strong>Estimated Duration:</strong> {{ $course->lessons->sum('duration_minutes') }} mins</p>

                        @if($isEnrolled)
                            <a href="{{ route('student.my-courses') }}" class="btn btn-primary">Continue Learning</a>
                        @else
                            <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                @csrf
                                <button class="btn btn-primary">Enroll Now</button>
                            </form>
                        @endif

                        @if($isEnrolled && $course->quizzes->isNotEmpty())
                            <div class="mt-4">
                                <h5>Quizzes</h5>
                                @foreach($course->quizzes as $quiz)
                                    @php
                                        $quizAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->latest()->first();
                                    @endphp
                                    <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $quiz->title }}</strong>
                                            <div class="small text-muted">Passing score: {{ $quiz->passing_score }}%</div>
                                        </div>
                                        @if($quizAttempt)
                                            <a href="{{ route('student.quiz.result', [$userEnrollment, $quiz]) }}" class="btn btn-sm btn-outline-secondary">View Result</a>
                                        @else
                                            <a href="{{ route('student.quiz.show', [$userEnrollment, $quiz]) }}" class="btn btn-sm btn-primary">Take Quiz</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Course Content</h5>
                    </div>
                    <div class="card-body">
                        @foreach($course->sections as $section)
                            <div class="mb-3">
                                <h6>{{ $section->title }}</h6>
                                <ul class="list-unstyled small">
                                    @foreach($section->lessons as $lesson)
                                        <li class="mb-1">• {{ $lesson->title }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
