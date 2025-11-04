<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RoleAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');
        $hasLabelColumn = Schema::hasColumn('roles', 'label');

        // Create roles with guard + label metadata
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => $guard],
            $hasLabelColumn ? ['label' => 'Administrator'] : []
        );

        if ($hasLabelColumn && $adminRole->label !== 'Administrator') {
            $adminRole->label = 'Administrator';
            $adminRole->save();
        }

        $lecturerRole = Role::firstOrCreate(
            ['name' => 'lecturer', 'guard_name' => $guard],
            $hasLabelColumn ? ['label' => 'Lecturer'] : []
        );

        if ($hasLabelColumn && $lecturerRole->label !== 'Lecturer') {
            $lecturerRole->label = 'Lecturer';
            $lecturerRole->save();
        }

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@cybercore.test'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // Assign admin role to the admin user
        if (!$adminUser->hasRole($adminRole)) {
            $adminUser->assignRole($adminRole);
        }

        if ($adminUser->role_id !== $adminRole->id) {
            $adminUser->role_id = $adminRole->id;
        }

        if (method_exists($adminUser, 'hasVerifiedEmail') && ! $adminUser->hasVerifiedEmail()) {
            $adminUser->forceFill(['email_verified_at' => now()]);
        }

        $adminUser->save();
    }
}
