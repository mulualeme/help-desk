<?php

namespace App\Policies;

use App\Models\Sla;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SlaPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view sla');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sla $sla): bool
    {
        return $user->hasPermissionTo('view sla');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create sla');
    }

    /**
     * Determine whether the user can update any models.
     */
    public function updateAny(User $user): bool
    {
        return $user->hasPermissionTo('edit sla');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sla $sla): bool
    {
        return $user->hasPermissionTo('edit sla');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete sla');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sla $sla): bool
    {
        return $user->hasPermissionTo('delete sla');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sla $sla): bool
    {
        return $user->hasPermissionTo('edit sla');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sla $sla): bool
    {
        return $user->hasPermissionTo('delete sla');
    }
}
