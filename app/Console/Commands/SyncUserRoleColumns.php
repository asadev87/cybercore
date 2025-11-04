<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncUserRoleColumns extends Command
{
    protected $signature = 'roles:sync-user-columns';

    protected $description = 'Synchronize the users.role_id column with their assigned primary role.';

    public function handle(): int
    {
        $updated = 0;

        User::with('roles')->chunkById(200, function ($users) use (&$updated) {
            foreach ($users as $user) {
                $primaryRole = $user->roles->first();
                $roleId = $primaryRole?->id;

                if ($user->role_id !== $roleId) {
                    $user->forceFill(['role_id' => $roleId])->save();
                    $updated++;
                }
            }
        });

        $this->info("Synchronized role_id for {$updated} users.");

        return self::SUCCESS;
    }
}
