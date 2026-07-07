<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function view(User $user, Lesson $lesson): bool
    {
        return $user->hasRole(User::ROLE_STUDENT)
            && $lesson->course()->whereHas('enrollments', fn ($query) => $query->where('user_id', $user->id))->exists();
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $user->id === $lesson->course->instructor_id || $user->hasRole(User::ROLE_ADMIN);
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $this->update($user, $lesson);
    }
}
