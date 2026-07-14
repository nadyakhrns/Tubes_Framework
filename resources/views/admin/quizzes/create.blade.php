<x-app-layout title="Create Quiz">
    <div class="container-fluid py-4">

        <div class="mb-3">
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Quizzes
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Create New Quiz</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.quizzes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Course <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Final Quiz - HTML Basics">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Brief description of this quiz...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                            <input type="number" name="passing_score" class="form-control @error('passing_score') is-invalid @enderror" value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                            @error('passing_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Minimum score to pass (0-100).</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time Limit (minutes)</label>
                            <input type="number" name="time_limit_minutes" class="form-control @error('time_limit_minutes') is-invalid @enderror" value="{{ old('time_limit_minutes') }}" min="1" placeholder="Leave empty for no time limit">
                            @error('time_limit_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" {{ old('is_published', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">Published</label>
                    </div>
                    <hr>
                    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
