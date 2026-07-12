<?php

namespace App\Http\Requests\Admin;

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
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Course wajib dipilih.',
            'title.required' => 'Judul kuis wajib diisi.',
            'passing_score.required' => 'Passing score wajib diisi.',
            'passing_score.min' => 'Passing score minimal 0.',
            'passing_score.max' => 'Passing score maksimal 100.',
        ];
    }
}
