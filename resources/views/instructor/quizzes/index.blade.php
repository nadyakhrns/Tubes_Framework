<x-app-layout title="Quiz — {{ $course->title }}">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4><i class="bi bi-journal-check"></i> Quiz</h4>
                <p class="text-muted mb-0"><i class="bi bi-book"></i> Course: <strong>{{ $course->title }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('instructor.courses.quizzes.create', $course) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Buat Quiz Baru
                </a>
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Courses
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari judul quiz...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Judul Quiz</th>
                        <th>Course</th>
                        <th class="text-center">Soal</th>
                        <th class="text-center">Passing Score</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td class="text-center">
                                <span class="badge bg-info">{{ $quiz->questions_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $quiz->passing_score }}%</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $quiz->statusColor() }}">{{ $quiz->statusLabel() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('instructor.courses.quizzes.show', [$quiz->course, $quiz]) }}"
                                   class="btn btn-sm btn-outline-info" title="Kelola Soal">
                                    <i class="bi bi-list-check"></i>
                                </a>
                                <a href="{{ route('instructor.courses.quizzes.edit', [$quiz->course, $quiz]) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @unless($quiz->isPendingReview() || $quiz->isPublished())
                                    <form action="{{ route('instructor.courses.quizzes.destroy', [$quiz->course, $quiz]) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Hapus quiz ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                                Belum ada quiz. Buka halaman course untuk membuat quiz baru.
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
