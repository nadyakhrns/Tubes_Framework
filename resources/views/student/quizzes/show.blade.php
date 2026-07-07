<x-app-layout title="Quiz">
    <div class="container-fluid py-4">
        <x-flash-message />

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h4 class="mb-0">{{ $quiz->title }}</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $quiz->description }}</p>
                <form method="POST" action="{{ route('student.quiz.submit', [$enrollment, $quiz]) }}">
                    @csrf
                    @foreach($quiz->questions as $question)
                        <div class="border rounded p-3 mb-3">
                            <h6>{{ $loop->iteration }}. {{ $question->question }}</h6>
                            @foreach($question->options as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}o{{ $option->id }}" value="{{ $option->id }}">
                                    <label class="form-check-label" for="q{{ $question->id }}o{{ $option->id }}">{{ $option->option_text }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <button class="btn btn-primary">Submit Quiz</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
