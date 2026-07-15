<x-app-layout title="Edit User">

    <div class="container-fluid py-4">

        <a href="{{ route('admin.instructors.index') }}"
           class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <form method="POST"
                      action="{{ route('admin.instructors.update',$instructor) }}">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label class="form-label">Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name',$instructor->name) }}"
                            required>

                        @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email',$instructor->email) }}"
                            required>

                        @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Role</label>

                        <select name="role" class="form-select">

                            <option value="admin"
                                @selected($instructor->role=='admin')>
                                Admin
                            </option>

                            <option value="instructor"
                                @selected($instructor->role=='instructor')>
                                Instructor
                            </option>

                            <option value="student"
                                @selected($instructor->role=='student')>
                                Student
                            </option>

                        </select>

                        @error('role')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Password</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control">

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti password.
                        </small>

                        @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror

                    </div>

                    <button class="btn btn-primary">
                        Update
                    </button>

                    <a href="{{ route('admin.instructors.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>