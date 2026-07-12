<x-app-layout title="Quizzes">
    <div class="container-fluid py-4">
        <x-flash-message />

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Quizzes</h4>
            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create Quiz
            </a>
        </div>

        {{-- Filter: Search + Course dropdown --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by title...">
            </div>
            <div class="col-md-3">
                <select name="course_id" class="form-select">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
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
                        <th>Course</th>
                        <th class="text-center">Questions</th>
                        <th class="text-center">Passing Score</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td>{{ $loop->iteration + ($quizzes->currentPage() - 1) * $quizzes->perPage() }}</td>
                            <td class="fw-semibold">{{ $quiz->title }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $quiz->course?->title ?? '-' }}</span>
                            </td>
                            {{-- questions_count dari withCount('questions') --}}
                            <td class="text-center">
                                <span class="badge bg-info">{{ $quiz->questions_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $quiz->passing_score }}%</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $quiz->is_published ? 'success' : 'secondary' }}">
                                    {{ $quiz->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-sm btn-outline-info" title="Manage Questions">
                                    <i class="bi bi-list-check"></i> Questions
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this quiz and all its questions?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                                No quizzes found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $quizzes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
