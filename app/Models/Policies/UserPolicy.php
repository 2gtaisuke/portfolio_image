<?php

namespace App\Models\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $login_user, User $user)
    {
        return $login_user->isMyself($user);
    }

    public function delete(User $login_user, User $user)
    {
        return $login_user->isMyself($user);
    }
}
