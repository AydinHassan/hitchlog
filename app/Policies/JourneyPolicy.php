<?php

namespace App\Policies;

use App\Journey;
use App\User;

class JourneyPolicy
{
    public function update(User $user, Journey $journey): bool
    {
        return $user->id === (int) $journey->user_id;
    }

    public function delete(User $user, Journey $journey): bool
    {
        return $user->id === (int) $journey->user_id;
    }
}
