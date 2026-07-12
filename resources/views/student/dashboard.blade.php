<x-app-layout title="Student Dashboard">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Enrolled Courses</h6>
                        <h3 class="mb-0">{{ $stats['enrolled_courses'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Overall Progress</h6>
                        <h3 class="mb-0">{{ $stats['overall_progress'] }}%</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Latest Enrolled Courses</h5>
                <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-primary">Explore Courses</a>
            </div>
            <div class="card-body">
                @if($latestEnrollments->isEmpty())
                    <p class="text-muted mb-0">You have not enrolled in any course yet.</p>
                @else
                    <div class="row g-3">
                        @foreach($latestEnrollments as $enrollment)
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="mb-1">{{ $enrollment->course->title }}</h6>
                                    <p class="text-muted small mb-2">{{ $enrollment->course->category?->name }} • {{ $enrollment->course->instructor?->name }}</p>
                                    <a href="{{ route('student.courses.show', $enrollment->course) }}" class="btn btn-sm btn-outline-primary">View Course</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
