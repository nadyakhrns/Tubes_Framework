<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => [
                Rule::unique('enrollments', 'course_id')->where(fn ($query) => $query->where('user_id', $this->user()?->id)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.unique' => 'You are already enrolled in this course.',
        ];
    }
}
