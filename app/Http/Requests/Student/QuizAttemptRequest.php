<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizAttemptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['nullable', 'array'],
            'quiz_id' => [
                Rule::unique('quiz_attempts', 'quiz_id')->where(fn ($query) => $query->where('user_id', $this->user()?->id)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'quiz_id.unique' => 'You already attempted this quiz.',
        ];
    }
}
