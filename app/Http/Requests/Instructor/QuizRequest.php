<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'passing_score'      => ['required', 'integer', 'min:0', 'max:100'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'         => 'Judul kuis wajib diisi.',
            'passing_score.required' => 'Passing score wajib diisi.',
            'passing_score.min'      => 'Passing score minimal 0.',
            'passing_score.max'      => 'Passing score maksimal 100.',
        ];
    }
}
