<x-app-layout title="Users">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Users</h4>

            <a href="{{ route('admin.instructors.create') }}" class="btn btn-primary">
                Create User
            </a>
        </div>

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Search users">
            </div>

            <div class="col-md-2">
                <button class="btn btn-outline-secondary">
                    Search
                </button>
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                @php
                                    $color = match($user->role){
                                        'admin' => 'danger',
                                        'instructor' => 'primary',
                                        default => 'success'
                                    };
                                @endphp

                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <td>

                                <a href="{{ route('admin.instructors.edit',$user) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form action="{{ route('admin.instructors.destroy',$user) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus user ini?')">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center">
                                Tidak ada user.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

                {{ $users->links() }}

            </div>
        </div>
    </div>
</x-app-layout>