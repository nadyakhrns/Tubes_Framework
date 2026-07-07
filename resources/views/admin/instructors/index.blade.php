<x-app-layout title="Instructors">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Instructors</h4>
            <a href="{{ route('admin.instructors.create') }}" class="btn btn-primary">Create Instructor</a>
        </div>

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search instructors">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($instructors as $instructor)
                        <tr>
                            <td>{{ $instructor->name }}</td>
                            <td>{{ $instructor->email }}</td>
                            <td>
                                <a href="{{ route('admin.instructors.edit', $instructor) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.instructors.destroy', $instructor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete instructor?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $instructors->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
