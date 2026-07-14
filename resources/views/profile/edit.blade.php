<x-app-layout title="Profile">

    <div class="container py-4">

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body text-center">

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D6EFD&color=fff&size=128"
                    class="rounded-circle shadow mb-3"
                    width="120"
                    height="120"
                    alt="Avatar">

                <h3 class="fw-bold mb-1">
                    {{ auth()->user()->name }}
                </h3>

                <p class="text-muted mb-2">
                    {{ auth()->user()->email }}
                </p>
        <div class="row mt-3 text-center">

    <div class="col-md-4">
        <small class="text-muted d-block">Role</small>
        <strong>{{ ucfirst(auth()->user()->role) }}</strong>
    </div>

    <div class="col-md-4">
        <small class="text-muted d-block">User ID</small>
        <strong>#{{ auth()->user()->id }}</strong>
    </div>

    <div class="col-md-4">
        <small class="text-muted d-block">Joined</small>
        <strong>{{ auth()->user()->created_at?->format('d M Y') }}</strong>
    </div>

    </div>

                @php
                    $badge = match(auth()->user()->role){
                        'admin' => 'danger',
                        'instructor' => 'warning',
                        default => 'primary'
                    };
                @endphp

                <span class="badge bg-{{ $badge }} px-3 py-2 text-uppercase">
                    {{ auth()->user()->role }}
                </span>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            👤 Profile Information
                        </h5>
                    </div>

                    <div class="card-body">
                        @if(auth()->user()->role === 'admin')
<div class="mb-3">
    <label class="form-label">Role</label>

    <select name="role" form="send-verification" class="form-select">
        <option value="admin" {{ auth()->user()->role == 'admin' ? 'selected' : '' }}>
            Admin
        </option>

        <option value="instructor" {{ auth()->user()->role == 'instructor' ? 'selected' : '' }}>
            Instructor
        </option>

        <option value="student" {{ auth()->user()->role == 'student' ? 'selected' : '' }}>
            Student
        </option>
    </select>
</div>
@endif
                        @include('profile.partials.update-profile-information-form')
                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            🔒 Change Password
                        </h5>
                    </div>

                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>

                </div>

            </div>


            <div class="col-12">

                <div class="card border-danger shadow-sm">

                    <div class="card-header bg-danger text-white">

                        <h5 class="mb-0">
                            ⚠ Danger Zone
                        </h5>

                    </div>

                    <div class="card-body">

                        @include('profile.partials.delete-user-form')

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>