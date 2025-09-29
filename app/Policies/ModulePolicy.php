<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * This method is called before any other methods in the policy.
     * If it returns true, the user is authorized.
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any modules in the admin index.
     * Both admins and lecturers should be able to see the module list page.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['admin', 'lecturer']);
    }

    /**
     * Determine whether the user can view the given module.
     * A lecturer can only view a module if they own it.
     */
    public function view(User $user, Module $module)
    {
        return $user->id === $module->user_id;
    }

    /**
     * Determine whether the user can create modules.
     * Both admins and lecturers can create modules.
     */
    public function create(User $user)
    {
        return $user->hasRole(['admin', 'lecturer']);
    }

    /**
     * Determine whether the user can update the model.
     * A lecturer can only update a module if they own it.
     */
    public function update(User $user, Module $module)
    {
        return $user->id === $module->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * The before() method grants access to admins. For all other users,
     * this will explicitly return false, preventing deletion.
     */
    public function delete(User $user, Module $module)
    {
        return false;
    }

    /**
     * Determine whether the user can assign a lecturer to a module.
     * Only admins can assign lecturers, which is handled by the before() method.
     */
    public function assignLecturer(User $user)
    {
        return false;
    }
}