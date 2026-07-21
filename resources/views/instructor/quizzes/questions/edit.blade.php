<x-app-layout title="Edit Soal — {{ $quiz->title }}">
    <div class="container-fluid py-4">

        <div class="mb-3">
            <a href="{{ route('instructor.quizzes.show', $quiz) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke {{ $quiz->title }}
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Soal</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('instructor.quizzes.questions.update', [$quiz, $question]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea name="question" class="form-control @error('question') is-invalid @enderror"
                                  rows="3" required>{{ old('question', $question->question) }}</textarea>
                        @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="number" name="points" class="form-control"
                               value="{{ old('points', $question->points) }}" min="1" style="max-width: 120px;">
                    </div>

                    <hr>
                    <h6 class="mb-3">
                        <i class="bi bi-list-ul"></i> Pilihan Jawaban
                        <small class="text-muted">(Pilih satu jawaban yang benar)</small>
                    </h6>

                    @foreach($question->options as $i => $option)
                        <div class="card border mb-2">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check">
                                        <input type="radio"
                                               name="correct_option"
                                               value="{{ $i }}"
                                               class="form-check-input"
                                               id="correct_{{ $i }}"
                                               {{ old("correct_option", $option->is_correct ? $i : null) == $i && (old("correct_option") !== null ? true : $option->is_correct) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="correct_{{ $i }}">
                                            {{ chr(65 + $i) }}.
                                        </label>
                                    </div>
                                    <input type="hidden" name="options[{{ $i }}][is_correct]"
                                           value="{{ $option->is_correct ? '1' : '0' }}" id="is_correct_{{ $i }}">
                                    <div class="flex-grow-1">
                                        <input type="text"
                                               name="options[{{ $i }}][option_text]"
                                               class="form-control form-control-sm"
                                               value="{{ old("options.{$i}.option_text", $option->option_text) }}"
                                               required
                                               placeholder="Opsi {{ chr(65 + $i) }}...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <hr>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                    <a href="{{ route('instructor.quizzes.show', $quiz) }}" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[name="correct_option"]');

            function syncCorrect() {
                radios.forEach(function (radio) {
                    document.getElementById('is_correct_' + radio.value).value = radio.checked ? '1' : '0';
                });
            }

            radios.forEach(radio => radio.addEventListener('change', syncCorrect));
            syncCorrect();
        });
    </script>
</x-app-layout>
