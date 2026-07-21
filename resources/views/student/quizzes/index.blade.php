<x-app-layout title="Quizzes">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Quizzes</h5>
            </div>
            <div class="card-body">
                @if($quizzes->isEmpty())
                    <p class="text-muted mb-0">No quizzes available yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Course</th>
                                    <th>Quiz Title</th>
                                    <th>Passing Score</th>
                                    <th>Status</th>
                                    <th>Your Score</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quizzes as $quiz)
                                    @php
                                        $attempt = $attempts[$quiz->id] ?? null;
                                        $isEnrolled = in_array($quiz->course_id, $enrolledCourseIds);
                                        // The enrollment might be null if not enrolled, but we only use it if enrolled.
                                        $enrollment = $quiz->course->enrollments->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($quiz->course->slug)
                                                <a href="{{ route('student.courses.show', $quiz->course) }}" class="text-decoration-none text-dark fw-medium">
                                                    {{ $quiz->course->title }}
                                                </a>
                                            @else
                                                <span class="text-dark fw-medium">{{ $quiz->course->title }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $quiz->title }}</td>
                                        <td>{{ $quiz->passing_score }}</td>
                                        <td>
                                            @if($attempt)
                                                @if($attempt->passed)
                                                    <span class="badge bg-success">Passed</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Not Attempted</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt)
                                                <strong>{{ $attempt->score }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt)
                                                <a href="{{ route('student.quiz.result', [$enrollment, $quiz]) }}" class="btn btn-sm btn-outline-secondary">View Result</a>
                                            @else
                                                @if($isEnrolled)
                                                    <a href="{{ route('student.quiz.show', [$enrollment, $quiz]) }}" class="btn btn-sm btn-primary">Take Quiz</a>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="showNotEnrolledAlert()">Take Quiz</button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $quizzes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function showNotEnrolledAlert() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: 'Anda tidak bisa mengikuti quiz dikarenakan tidak mengikuti course ini.',
                    confirmButtonColor: '#0d6efd'
                });
            } else {
                alert('Anda tidak bisa mengikuti quiz dikarenakan tidak mengikuti course ini.');
            }
        }
    </script>
</x-app-layout>
