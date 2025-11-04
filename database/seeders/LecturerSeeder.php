<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the lecturer role
        $guard = config('auth.defaults.guard', 'web');
        $hasLabelColumn = Schema::hasColumn('roles', 'label');
        $lecturerRole = Role::firstOrCreate(
            ['name' => 'lecturer', 'guard_name' => $guard],
            $hasLabelColumn ? ['label' => 'Lecturer'] : []
        );

        if ($hasLabelColumn && $lecturerRole->label !== 'Lecturer') {
            $lecturerRole->label = 'Lecturer';
            $lecturerRole->save();
        }

        // Create a default lecturer user
        $lecturerUser = User::firstOrCreate(
            ['email' => 'lecturer@cybercore.test'],
            [
                'name' => 'Lecturer User',
                'password' => bcrypt('password'),
            ]
        );

        // Assign the lecturer role to the user
        if (!$lecturerUser->hasRole($lecturerRole)) {
            $lecturerUser->assignRole($lecturerRole);
        }

        if ($lecturerUser->role_id !== $lecturerRole->id) {
            $lecturerUser->role_id = $lecturerRole->id;
        }

        if (method_exists($lecturerUser, 'hasVerifiedEmail') && ! $lecturerUser->hasVerifiedEmail()) {
            $lecturerUser->forceFill(['email_verified_at' => now()]);
        }

        $lecturerUser->save();
    }
}
