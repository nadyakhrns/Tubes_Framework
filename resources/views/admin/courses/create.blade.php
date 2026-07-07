<x-app-layout title="Create Course">
    <div class="container-fluid py-4">
        <a href="{{ route('admin.courses.index') }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Courses
        </a>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructor</label>
                        <select name="instructor_id" class="form-select">
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Learning Objectives</label>
                        <textarea name="learning_objectives" class="form-control" rows="4">{{ old('learning_objectives') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Approval Status</label>
                        <select name="approval_status" class="form-select">
                            <option value="pending" {{ old('approval_status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input" {{ old('is_published', true) ? 'checked' : '' }}>
                        <label class="form-check-label">Published</label>
                    </div>
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
