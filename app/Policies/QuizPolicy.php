<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function view(User $user, Quiz $quiz): bool
    {
        return $user->hasRole(User::ROLE_STUDENT)
            && $quiz->course()->whereHas('enrollments', fn ($query) => $query->where('user_id', $user->id))->exists();
    }

    public function submit(User $user, Quiz $quiz): bool
    {
        return $this->view($user, $quiz);
    }

    public function result(User $user, Quiz $quiz): bool
    {
        return $this->view($user, $quiz);
    }
}
