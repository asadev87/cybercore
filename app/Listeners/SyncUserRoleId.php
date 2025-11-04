<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Events\RoleAssigned;
use Spatie\Permission\Events\RoleRemoved;

class SyncUserRoleId
{
    public function handle(RoleAssigned|RoleRemoved $event): void
    {
        $model = $event->model;

        if (! $model instanceof User) {
            return;
        }

        $this->syncPrimaryRoleId($model);
    }

    private function syncPrimaryRoleId(User $user): void
    {
        $currentPrimaryRole = $user->roles()->orderBy('id')->first();
        $roleId = $currentPrimaryRole?->id;

        if ($user->role_id === $roleId) {
            return;
        }

        $user->forceFill(['role_id' => $roleId])->save();
    }
}
