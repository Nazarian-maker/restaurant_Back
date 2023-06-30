<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return in_array($user->role()->first()->name, [Role::IS_SUPERADMIN,Role::IS_ADMIN,Role::IS_WAITER]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return in_array($user->role()->first()->name, [Role::IS_SUPERADMIN,Role::IS_ADMIN,Role::IS_WAITER]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return in_array($user->role()->first()->name, [Role::IS_SUPERADMIN,Role::IS_ADMIN,Role::IS_WAITER]);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return in_array($user->role()->first()->name, [Role::IS_SUPERADMIN,Role::IS_ADMIN,Role::IS_WAITER]);
    }
}
