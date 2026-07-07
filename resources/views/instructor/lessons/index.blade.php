<x-app-layout title="Lessons">
    <div class="container-fluid py-4">
        <x-flash-message />

        <a href="{{ route('instructor.courses.index') }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Courses
        </a>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4>Lessons for {{ $course->title }}</h4>
                <p class="text-muted mb-0">Manage lessons for this course.</p>
            </div>
            <a href="{{ route('instructor.courses.lessons.create', $course) }}" class="btn btn-primary">Create Lesson</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Section</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lessons as $lesson)
                        <tr>
                            <td>{{ $lesson->title }}</td>
                            <td>{{ $lesson->section?->title }}</td>
                            <td>{{ $lesson->order }}</td>
                            <td>
                                <a href="{{ route('instructor.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('instructor.courses.lessons.destroy', [$course, $lesson]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete lesson?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $lessons->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
