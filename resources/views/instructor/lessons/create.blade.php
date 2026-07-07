<x-app-layout title="Create Lesson">
    <div class="container-fluid py-4">
        <a href="{{ route('instructor.courses.lessons.index', $course) }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Lesson
        </a>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('instructor.courses.lessons.store', $course) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <select name="section_id" class="form-select">
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="4">{{ old('content') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">YouTube Video URL</label>
                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lesson PDF</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (Minutes)</label>
                        <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', 10) }}">
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
                    <a href="{{ route('instructor.courses.lessons.index', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
