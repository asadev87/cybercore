<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $this->call([
        ModuleSeeder::class,
    ]);
        $this->call(\Database\Seeders\DemoQuizSeeder::class);
        $this->call(RoleAndAdminSeeder::class);
        $this->call(LecturerSeeder::class);
        $this->call(CourseSeeder::class);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
