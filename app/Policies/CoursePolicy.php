<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function view(?User $user, Course $course): bool
    {
        if ($user && $user->hasRole(User::ROLE_STUDENT)) {
            return $course->is_published && $course->approval_status === 'approved';
        }

        return false;
    }

    public function enroll(User $user, Course $course): bool
    {
        return $user->hasRole(User::ROLE_STUDENT)
            && $course->is_published
            && $course->approval_status === 'approved';
    }

    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->instructor_id || $user->hasRole(User::ROLE_ADMIN);
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }
}
