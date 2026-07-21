<x-app-layout title="My Quizzes">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4><i class="bi bi-journal-check"></i> Manajemen Quizzes</h4>
                <p class="text-muted mb-0">Kelola semua quiz dari seluruh course Anda.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('instructor.quizzes.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Buat Quiz Baru
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari judul quiz...">
            </div>
            <div class="col-md-3">
                <select name="course_id" class="form-select">
                    <option value="">Semua Course</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->title }}
                        </option>
                    @endforeach
                </select>
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
                <a href="{{ route('instructor.quizzes.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                                <a href="{{ route('instructor.quizzes.show', $quiz) }}"
                                   class="btn btn-sm btn-outline-info" title="Kelola Soal">
                                    <i class="bi bi-list-check"></i>
                                </a>
                                <a href="{{ route('instructor.quizzes.edit', $quiz) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @unless($quiz->isPendingReview() || $quiz->isPublished())
                                    <form action="{{ route('instructor.quizzes.destroy', $quiz) }}"
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
                                Belum ada quiz yang Anda buat.
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
