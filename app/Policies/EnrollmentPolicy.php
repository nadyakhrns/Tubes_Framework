<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->id === $enrollment->user_id;
    }

    public function complete(User $user, Enrollment $enrollment): bool
    {
        return $this->view($user, $enrollment);
    }
}
