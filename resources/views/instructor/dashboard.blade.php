<x-app-layout title="Instructor Dashboard">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="row g-4 mb-4">
            @foreach([
                ['label' => 'My Courses', 'value' => $stats['courses'], 'icon' => 'bi-book'],
                ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'bi-check-circle'],
                ['label' => 'Pending Approval', 'value' => $stats['pending'], 'icon' => 'bi-hourglass'],
            ] as $stat)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">{{ $stat['label'] }}</h6>
                                <h3 class="mb-0">{{ $stat['value'] }}</h3>
                            </div>
                            <i class="bi {{ $stat['icon'] }} fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Courses</h5>
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-sm btn-primary">Manage Courses</a>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentCourses as $course)
                        <tr>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->category?->name }}</td>
                            <td>
                                <span class="badge bg-{{ $course->is_published ? 'success' : 'secondary' }}">{{ $course->is_published ? 'Published' : 'Draft' }}</span>
                                <span class="badge bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($course->approval_status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
