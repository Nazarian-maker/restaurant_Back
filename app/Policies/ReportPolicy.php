<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return in_array($user->role()->first()->name, [Role::IS_SUPERADMIN,Role::IS_ADMIN]);
    }
}
