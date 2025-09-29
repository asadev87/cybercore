<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the lecturer role
        $lecturerRole = Role::firstOrCreate(['name' => 'lecturer']);

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
    }
}