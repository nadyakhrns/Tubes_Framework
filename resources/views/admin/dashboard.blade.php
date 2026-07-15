<x-app-layout title="Admin Dashboard">
    <div class="container-fluid py-4">
        <x-flash-message />

        {{-- Stats Cards --}}
        <div class="row g-4 mb-4">
            @foreach([
                ['label' => 'Courses', 'value' => $stats['courses'], 'color' => 'primary'],
                ['label' => 'Approved', 'value' => $stats['approved_courses'], 'color' => 'success'],
                ['label' => 'Pending Course', 'value' => $stats['pending_courses'], 'color' => 'warning'],
                ['label' => 'Instructors', 'value' => $stats['instructors'], 'color' => 'info'],
                ['label' => 'Quiz Pending Review', 'value' => $stats['quizzes_pending'], 'color' => 'danger'],
            ] as $stat)
                <div class="col-md-3 col-sm-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">{{ $stat['label'] }}</h6>
                            <h3 class="mb-0 text-{{ $stat['color'] }}">{{ $stat['value'] }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">
            {{-- Recent Courses --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Courses</h5>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-primary">Manage Courses</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Instructor</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentCourses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->category?->name }}</td>
                                    <td>{{ $course->instructor?->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($course->approval_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pending Quizzes --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-hourglass-split text-warning"></i> Quiz Pending Review
                        </h5>
                        <a href="{{ route('admin.quizzes.index', ['status' => 'pending_review']) }}"
                           class="btn btn-sm btn-warning">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @forelse($pendingQuizzes as $quiz)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <div>
                                    <div class="fw-semibold">{{ $quiz->title }}</div>
                                    <small class="text-muted">
                                        {{ $quiz->course?->title }} · {{ $quiz->creator?->name }}
                                    </small>
                                </div>
                                <a href="{{ route('admin.quizzes.show', $quiz) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-eye"></i> Review
                                </a>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-check-all fs-3 d-block mb-2 text-success"></i>
                                <p class="mb-0">Semua quiz sudah direview. 🎉</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
