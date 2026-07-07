<x-app-layout title="Create Section">
    <div class="container-fluid py-4">
        <a href="{{ route('instructor.courses.sections.index', $course) }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Section
        </a>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('instructor.courses.sections.store', $course) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 1) }}">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input" {{ old('is_published', true) ? 'checked' : '' }}>
                        <label class="form-check-label">Published</label>
                    </div>
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('instructor.courses.sections.index', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
