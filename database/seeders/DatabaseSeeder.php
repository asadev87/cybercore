<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // The order of seeders is important.
        // Roles must be created before users can be assigned to them.
        // Users (lecturers) must be created before courses can be assigned to them.
        $this->call(RoleSeeder::class);
        $this->call([
            RoleAndAdminSeeder::class,
            LecturerSeeder::class,
            ModuleSeeder::class,
            CourseSeeder::class,
            DemoQuizSeeder::class, // Keep this for existing demo data
            QuestionNotesSeeder::class,
            BadgeSeeder::class,
        ]);
    }
}
