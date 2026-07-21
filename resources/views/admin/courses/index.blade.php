<x-app-layout title="Courses">
    <div class="container-fluid py-4">
        <x-flash-message />

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Courses</h4>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create Course
            </a>
        </div>

        {{-- Filter: Search + Category dropdown --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by title...">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Instructor</th>
                        {{-- Kolom baru: Content & Enrollments dari withCount --}}
                        <th class="text-center">Content</th>
                        <th class="text-center">Enrollments</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $loop->iteration + ($courses->currentPage() - 1) * $courses->perPage() }}</td>
                            <td>
                                <span class="fw-semibold">{{ $course->title }}</span>
                                @if($course->subtitle)
                                    <br><small class="text-muted">{{ Str::limit($course->subtitle, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($course->category)
                                    <span class="badge bg-light text-dark border">{{ $course->category->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $course->instructor?->name ?? '-' }}</td>
                            {{--
                                sections_count & lessons_count berasal dari withCount()
                                di CourseController. Tidak perlu query tambahan.
                            --}}
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $course->sections_count }} Sections</span>
                                <span class="badge bg-info">{{ $course->lessons_count }} Lessons</span>
                            </td>
                            {{-- enrollments_count = jumlah peserta yang enroll --}}
                            <td class="text-center">
                                <span class="badge bg-dark">
                                    <i class="bi bi-people-fill"></i> {{ $course->enrollments_count }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $course->is_published ? 'success' : 'secondary' }}">
                                    {{ $course->is_published ? 'Published' : 'Draft' }}
                                </span>
                                <span class="badge bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($course->approval_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.courses.edit', $course->slug ?: $course->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if($course->approval_status !== 'approved')
                                        <a href="{{ route('admin.courses.approve', $course->slug ?: $course->id) }}" class="btn btn-success" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                    @endif
                                    @if($course->approval_status !== 'rejected')
                                        <a href="{{ route('admin.courses.reject', $course->slug ?: $course->id) }}" class="btn btn-danger" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    @endif
                                    @if($course->is_published)
                                        <a href="{{ route('admin.courses.unpublish', $course->slug ?: $course->id) }}" class="btn btn-outline-secondary" title="Unpublish">
                                            <i class="bi bi-eye-slash"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.courses.publish', $course->slug ?: $course->id) }}" class="btn btn-outline-primary" title="Publish">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif
                                </div>
                                <form action="{{ route('admin.courses.destroy', $course->slug ?: $course->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this course?')" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                                No courses found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
