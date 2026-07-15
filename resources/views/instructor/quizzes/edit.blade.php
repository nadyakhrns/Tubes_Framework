<x-app-layout title="Edit Quiz — {{ $quiz->title }}">
    <div class="container-fluid py-4">

        <div class="mb-3">
            <a href="{{ route('instructor.courses.quizzes.show', [$course, $quiz]) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail Quiz
            </a>
        </div>

        @if($quiz->isPublished())
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>
                    Quiz ini sedang <strong>Published</strong>. Jika Anda menyimpan perubahan,
                    status akan dikembalikan ke <strong>Draft</strong> dan harus direview ulang oleh Admin.
                </div>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Quiz — <span class="text-primary">{{ $quiz->title }}</span></h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('instructor.courses.quizzes.update', [$course, $quiz]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul Quiz <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $quiz->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3">{{ old('description', $quiz->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                            <input type="number" name="passing_score"
                                   class="form-control @error('passing_score') is-invalid @enderror"
                                   value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                            @error('passing_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Batas Waktu (menit)</label>
                            <input type="number" name="time_limit_minutes"
                                   class="form-control @error('time_limit_minutes') is-invalid @enderror"
                                   value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1"
                                   placeholder="Kosongkan jika tidak ada batas waktu">
                            @error('time_limit_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                    <a href="{{ route('instructor.courses.quizzes.show', [$course, $quiz]) }}" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
