<x-app-layout title="Instructor Dashboard">
    <div class="container-fluid py-4">
        <x-flash-message />

        {{-- Notifikasi Quiz Published --}}
        @if($publishedQuizzes->count() > 0)
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5 mt-1"></i>
                <div>
                    <strong>Quiz Anda telah dipublish!</strong><br>
                    @foreach($publishedQuizzes as $q)
                        <span class="badge bg-success me-1">{{ $q->title }}</span>
                    @endforeach
                    <br><small class="text-muted">Student sudah bisa mengakses quiz-quiz di atas.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Notifikasi Quiz Rejected --}}
        @if($rejectedQuizzes->count() > 0)
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                <i class="bi bi-x-octagon-fill fs-5 mt-1"></i>
                <div>
                    <strong>Quiz berikut ditolak oleh Admin:</strong><br>
                    @foreach($rejectedQuizzes as $q)
                        <a href="{{ route('instructor.courses.quizzes.show', [$q->course, $q]) }}"
                           class="badge bg-danger text-decoration-none me-1">
                            {{ $q->title }} — {{ $q->rejection_note ? Str::limit($q->rejection_note, 50) : 'Lihat detail' }}
                        </a>
                    @endforeach
                    <br><small class="text-muted">Klik quiz di atas untuk melihat catatan penolakan dan memperbaikinya.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="row g-4 mb-4">
            @foreach([
                ['label' => 'My Courses', 'value' => $stats['courses'], 'icon' => 'bi-book', 'color' => 'primary'],
                ['label' => 'Courses Published', 'value' => $stats['published'], 'icon' => 'bi-check-circle', 'color' => 'success'],
                ['label' => 'Course Pending', 'value' => $stats['pending'], 'icon' => 'bi-hourglass', 'color' => 'warning'],
                ['label' => 'Total Quiz', 'value' => $stats['quizzes_total'], 'icon' => 'bi-journal-check', 'color' => 'info'],
                ['label' => 'Quiz Published', 'value' => $stats['quizzes_published'], 'icon' => 'bi-patch-check', 'color' => 'success'],
                ['label' => 'Quiz Pending Review', 'value' => $stats['quizzes_pending'], 'icon' => 'bi-hourglass-split', 'color' => 'warning'],
                ['label' => 'Quiz Ditolak', 'value' => $stats['quizzes_rejected'], 'icon' => 'bi-x-octagon', 'color' => 'danger'],
            ] as $stat)
                <div class="col-md-3 col-sm-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">{{ $stat['label'] }}</h6>
                                <h3 class="mb-0">{{ $stat['value'] }}</h3>
                            </div>
                            <i class="bi {{ $stat['icon'] }} fs-3 text-{{ $stat['color'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Recent Courses --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Courses</h5>
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-sm btn-primary">Manage Courses</a>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentCourses as $course)
                        <tr>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->category?->name }}</td>
                            <td>
                                <span class="badge bg-{{ $course->is_published ? 'success' : 'secondary' }}">
                                    {{ $course->is_published ? 'Published' : 'Draft' }}
                                </span>
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
</x-app-layout>
