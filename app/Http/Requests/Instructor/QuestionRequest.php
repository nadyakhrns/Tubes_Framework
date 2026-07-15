<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi untuk menyimpan Question + Options sekaligus.
     *
     * Format request yang diharapkan:
     * - question: "Teks pertanyaan"
     * - points: 1
     * - options[0][option_text]: "Pilihan A"
     * - options[0][is_correct]: 1  (hanya option yang benar)
     * - options[1][option_text]: "Pilihan B"
     * - options[2][option_text]: "Pilihan C"
     * - options[3][option_text]: "Pilihan D"
     */
    public function rules(): array
    {
        return [
            'question'               => ['required', 'string'],
            'points'                 => ['nullable', 'integer', 'min:1'],
            'options'                => ['required', 'array', 'min:2', 'max:6'],
            'options.*.option_text'  => ['required', 'string', 'max:1000'],
            'options.*.is_correct'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'question.required'               => 'Teks pertanyaan wajib diisi.',
            'options.required'                => 'Minimal 2 opsi jawaban.',
            'options.min'                     => 'Minimal 2 opsi jawaban.',
            'options.*.option_text.required'  => 'Teks opsi jawaban wajib diisi.',
        ];
    }

    /** Pastikan tepat 1 option yang ditandai is_correct. */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $options       = $this->input('options', []);
            $correctCount  = collect($options)->filter(fn ($opt) => !empty($opt['is_correct']))->count();

            if ($correctCount === 0) {
                $validator->errors()->add('options', 'Harus ada minimal 1 jawaban yang benar.');
            }

            if ($correctCount > 1) {
                $validator->errors()->add('options', 'Hanya boleh ada 1 jawaban yang benar.');
            }
        });
    }
}
