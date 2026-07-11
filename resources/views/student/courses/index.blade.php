<x-app-layout title="Explore Courses">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="row g-3 align-items-end mb-4">
            <div class="col-md-6">
                <form method="GET" action="{{ route('student.courses.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by title" value="{{ request('search') }}">
                        <button class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <select class="form-select" onchange="window.location='{{ route('student.courses.index') }}?category_id=' + this.value">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('student.my-courses') }}" class="btn btn-outline-secondary">My Courses</a>
            </div>
        </div>

        <div class="row g-4">
            @foreach($courses as $course)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/'.$course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-light text-dark">{{ $course->category?->name }}</span>
                                <span class="badge bg-info text-dark">{{ ucfirst($course->level ?? 'beginner') }}</span>
                            </div>
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($course->description, 100) }}</p>
                            <p class="small mb-2"><strong>Instructor:</strong> {{ $course->instructor?->name }}</p>
                            <p class="small mb-3"><strong>Lessons:</strong> {{ $course->lessons_count }}</p>
                            <a href="{{ route('student.courses.show', $course) }}" class="btn btn-sm btn-primary">View Course</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    </div>
</x-app-layout>
