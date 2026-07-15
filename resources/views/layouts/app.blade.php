@props(['title' => 'Dashboard'])

@php
    $user = auth()->user();
    $role = $user?->role ?? 'guest';

    $dashboardRoutes = [
        'admin' => [
            'name' => 'admin.dashboard',
            'label' => 'Admin Dashboard',
            'icon' => 'bi-speedometer2'
        ],
        'instructor' => [
            'name' => 'instructor.dashboard',
            'label' => 'Instructor Dashboard',
            'icon' => 'bi-easel2'
        ],
        'student' => [
            'name' => 'student.dashboard',
            'label' => 'Student Dashboard',
            'icon' => 'bi-mortarboard'
        ],
    ];

    $currentDashboard = $dashboardRoutes[$role] ?? [
        'name' => 'dashboard',
        'label' => 'Dashboard',
        'icon' => 'bi-grid'
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="aurora-bg">

<div class="app-shell">

    <aside class="app-sidebar">

        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Skevva Logo" style="height: 32px; width: auto; object-fit: contain;">
            <span>Skevva</span>
        </a>

        <nav class="sidebar-nav">

            <a href="{{ route($currentDashboard['name']) }}"
               @class([
                    'sidebar-link',
                    'active' => request()->routeIs($currentDashboard['name'])
               ])>

                <span>{{ $currentDashboard['label'] }}</span>

            </a>

            @if($role === 'admin')

                <a href="{{ route('admin.categories.index') }}"
                   @class([
                        'sidebar-link',
                        'active' => request()->routeIs('admin.categories.*')
                   ])>

                    <span>Categories</span>

                </a>

                <a href="{{ route('admin.courses.index') }}"
                   @class([
                        'sidebar-link',
                        'active' => request()->routeIs('admin.courses.*')
                   ])>

                    <span>Courses</span>

                </a>

                {{-- Hanya tulisan yang diganti --}}
                <a href="{{ route('admin.instructors.index') }}"
                   @class([
                        'sidebar-link',
                        'active' => request()->routeIs('admin.instructors.*')
                   ])>

                    <span>Users</span>

                </a>

                <a href="{{ route('admin.quizzes.index') }}"
                   @class([
                        'sidebar-link',
                        'active' => request()->routeIs('admin.quizzes.*')
                   ])>

                    <span>Quizzes</span>

                </a>

            @endif

            <a href="{{ route('profile.edit') }}"
               @class([
                    'sidebar-link',
                    'active' => request()->routeIs('profile.*')
               ])>

                <span>Profile</span>

            </a>

        </nav>

    </aside>

    <div class="app-main">

        <nav class="topbar">

            <div>

                <p class="topbar-eyebrow">{{ ucfirst($role) }}</p>

                <h1>{{ $title ?? 'Dashboard' }}</h1>

            </div>

            <div class="dropdown">

                <button class="btn user-menu"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                    <span class="user-avatar">
                        {{ strtoupper(substr($user?->name ?? 'U',0,1)) }}
                    </span>

                    <span class="user-name">
                        {{ $user?->name }}
                    </span>

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                           href="{{ route('profile.edit') }}">
                            Profile
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <form method="POST"
                              action="{{ route('logout') }}">

                            @csrf

                            <button type="submit"
                                    class="dropdown-item">

                                Log out

                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </nav>

        <main class="content-area">

            {{ $slot }}

        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{!! addslashes(session('success')) !!}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{!! addslashes(session('error')) !!}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
    });
    @endif

});
</script>

</body>
</html>