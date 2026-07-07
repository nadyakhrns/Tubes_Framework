<x-app-layout title="Sections">
    <div class="container-fluid py-4">
        <x-flash-message />

        <a href="{{ route('instructor.courses.index') }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Courses
        </a>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4>Sections for {{ $course->title }}</h4>
                <p class="text-muted mb-0">Manage sections for this course.</p>
            </div>
            <a href="{{ route('instructor.courses.sections.create', $course) }}" class="btn btn-primary">Create Section</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sections as $section)
                        <tr>
                            <td>{{ $section->title }}</td>
                            <td>{{ $section->order }}</td>
                            <td><span class="badge bg-{{ $section->is_published ? 'success' : 'secondary' }}">{{ $section->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td>
                                <a href="{{ route('instructor.courses.sections.edit', [$course, $section]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('instructor.courses.sections.destroy', [$course, $section]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete section?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $sections->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
