<x-app-layout title="Learning">
    <div class="container-fluid py-4">
        <x-flash-message />

        <a href="{{ route('student.my-courses') }}"
        class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to My Courses
        </a>

        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Course Content</h6>
                    </div>
                    <div class="card-body">
                        @foreach($sections as $section)
                            <div class="mb-3">
                                <h6 class="fw-bold">{{ $section->title }}</h6>
                                <ul class="list-unstyled small">
                                    @foreach($section->lessons as $item)
                                        <li class="mb-1">
                                            <a href="{{ route('student.learning.show', [$enrollment, $item]) }}" class="text-decoration-none {{ $item->id === $lesson->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                                {{ $item->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="mb-3">{{ $lesson->title }}</h3>

                        @if($lesson->video_url)
                            <div class="ratio ratio-16x9 mb-3">
                                <!-- <iframe src="{{ $lesson->video_url }}" title="Lesson video" allowfullscreen></iframe> -->
                                 @php
                                    $videoId = null;

                                    if (str_contains($lesson->video_url, 'youtu.be/')) {
                                        $videoId = basename(parse_url($lesson->video_url, PHP_URL_PATH));
                                    } elseif (str_contains($lesson->video_url, 'watch?v=')) {
                                        parse_str(parse_url($lesson->video_url, PHP_URL_QUERY), $query);
                                        $videoId = $query['v'] ?? null;
                                    }
                                @endphp

                                @if($videoId)
                                    <iframe
                                        width="100%"
                                        height="500"
                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                        frameborder="0"
                                        allowfullscreen>
                                    </iframe>
                                @endif
                            </div>
                        @endif

                        @if($lesson->content)
                            <div class="mb-3">{!! $lesson->content !!}</div>
                        @endif

                        @if($lesson->attachment)
                            <a href="{{ asset('storage/'.$lesson->attachment) }}" class="btn btn-outline-secondary btn-sm" target="_blank">Download PDF</a>
                        @endif

                        <form method="POST" action="{{ route('student.learning.complete', [$enrollment, $lesson]) }}" class="mt-4">
                            @csrf
                            <button class="btn btn-success">Mark Lesson as Completed</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
