<x-app-layout title="Create Instructor">
    <div class="container-fluid py-4">
        <a href="{{ route('admin.instructors.index') }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Instructors
        </a>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.instructors.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <input type="hidden" name="role" value="{{ \App\Models\User::ROLE_INSTRUCTOR }}">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.instructors.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
