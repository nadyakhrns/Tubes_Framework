<x-app-layout title="Quiz Result">
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="mb-3">{{ $quiz->title }}</h3>
                <p><strong>Score:</strong> {{ $attempt->score }}%</p>
                <p><strong>Passing Score:</strong> {{ $quiz->passing_score }}%</p>
                <p><strong>Result:</strong> <span class="badge bg-{{ $attempt->passed ? 'success' : 'danger' }}">{{ $attempt->passed ? 'Passed' : 'Failed' }}</span></p>
                <p><strong>Correct Answers:</strong> {{ $attempt->correct_answers }}</p>
                <p><strong>Wrong Answers:</strong> {{ $attempt->total_questions - $attempt->correct_answers }}</p>

                <a href="{{ route('student.courses.show', $enrollment->course) }}" class="btn btn-primary">Back to Course</a>
            </div>
        </div>
    </div>
</x-app-layout>
