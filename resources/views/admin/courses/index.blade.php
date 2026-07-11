<x-app-layout title="Courses">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Courses</h4>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">Create Course</a>
        </div>

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search courses">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Instructor</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->category?->name }}</td>
                            <td>{{ $course->instructor?->name }}</td>
                            <td>
                                <span class="badge bg-{{ $course->is_published ? 'success' : 'secondary' }}">{{ $course->is_published ? 'Published' : 'Draft' }}</span>
                                <span class="badge bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($course->approval_status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete course?')">Delete</button>
                                </form>
                                @if($course->approval_status !== 'approved')
                                    <a href="{{ route('admin.courses.approve', $course) }}" class="btn btn-sm btn-success">Approve</a>
                                @endif
                                @if($course->approval_status !== 'rejected')
                                    <a href="{{ route('admin.courses.reject', $course) }}" class="btn btn-sm btn-danger">Reject</a>
                                @endif
                                @if($course->is_published)
                                    <a href="{{ route('admin.courses.unpublish', $course) }}" class="btn btn-sm btn-outline-secondary">Unpublish</a>
                                @else
                                    <a href="{{ route('admin.courses.publish', $course) }}" class="btn btn-sm btn-outline-primary">Publish</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
