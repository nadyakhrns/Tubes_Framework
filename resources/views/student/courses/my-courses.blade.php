<x-app-layout title="My Courses">
    <div class="container-fluid py-4">
        <x-flash-message />

        <a href="{{ route('student.dashboard') }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Dashboard
        </a>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">My Courses</h5>
            </div>
            <div class="card-body">
                @if($enrollments->isEmpty())
                    <p class="text-muted mb-0">You have not enrolled in any course yet.</p>
                @else
                    <div class="row g-4">
                        @foreach($enrollments as $enrollment)
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $enrollment->course->title }}</h6>
                                            <p class="small text-muted mb-0">{{ $enrollment->course->category?->name }}</p>
                                        </div>
                                        <span class="badge bg-success">Active</span>
                                    </div>

                                    @php
                                        $totalLessons = $enrollment->course->lessons()->count();
                                        $completedLessons = $enrollment->course->lessons()->whereHas('progresses', function ($query) use ($enrollment) {
                                            $query->where('user_id', $enrollment->user_id)->whereNotNull('completed_at');
                                        })->count();
                                        $progress = $totalLessons ? (int) round(($completedLessons / $totalLessons) * 100) : 0;
                                    @endphp

                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;"></div>
                                    </div>
                                    <p class="small text-muted mb-3">Progress: {{ $progress }}%</p>

                                    @php
                                        $lastLesson = $enrollment->course->lessons()->whereHas('progresses', function ($query) use ($enrollment) {
                                            $query->where('user_id', $enrollment->user_id)->whereNotNull('completed_at');
                                        })->latest('id')->first();
                                    @endphp

                                    @if($lastLesson)
                                        <p class="small mb-3">Last accessed lesson: {{ $lastLesson->title }}</p>
                                    @else
                                        <p class="small text-muted mb-3">Start your first lesson to build progress.</p>
                                    @endif

                                    @php
                                        $firstLesson = $enrollment->course->lessons()->first();
                                    @endphp

                                    @if($firstLesson)
                                        <a href="{{ route('student.learning.show', [$enrollment, $firstLesson]) }}" class="btn btn-sm btn-primary">Continue Learning</a>
                                    @else
                                        <span class="text-muted small">No lessons available yet.</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">
                    {{ $enrollments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
