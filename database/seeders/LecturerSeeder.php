<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lecturerRole = Role::firstOrCreate(['name' => 'lecturer']);

        $lecturer = User::firstOrCreate(
            ['email' => 'lecturer@cybercore.local'],
            [
                'name' => 'CyberCore Lecturer',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );

        $lecturer->assignRole($lecturerRole);
    }
}