<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    /**
     * Student boleh melihat halaman quiz jika:
     * - Quiz sudah published
     * - Student terdaftar (enrolled) di course yang memiliki quiz ini
     */
    public function view(User $user, Quiz $quiz): bool
    {
        if (!$quiz->isPublished()) {
            return false;
        }

        return $user->hasRole(User::ROLE_STUDENT)
            && $quiz->course()
                ->whereHas('enrollments', fn ($q) => $q->where('user_id', $user->id))
                ->exists();
    }

    /**
     * Student boleh submit jika syarat view terpenuhi.
     */
    public function submit(User $user, Quiz $quiz): bool
    {
        return $this->view($user, $quiz);
    }

    /**
     * Student boleh lihat hasil jika pernah attempt.
     */
    public function result(User $user, Quiz $quiz): bool
    {
        return $user->hasRole(User::ROLE_STUDENT)
            && $quiz->course()
                ->whereHas('enrollments', fn ($q) => $q->where('user_id', $user->id))
                ->exists();
    }
}
