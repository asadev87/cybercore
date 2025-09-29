<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the first user with the 'lecturer' role.
        $lecturer = User::role('lecturer')->first();

        if ($lecturer) {
            $courses = [
                [
                    'title' => 'Phishing Awareness',
                    'slug' => Str::slug('Phishing Awareness'),
                    'description' => 'Learn how to recognize and avoid phishing scams. This course covers common phishing techniques, how to spot fake emails and websites, and what to do if you suspect you\'ve been targeted.',
                    'pass_score' => 80,
                    'is_active' => true,
                ],
                [
                    'title' => 'Creating Strong Passwords',
                    'slug' => Str::slug('Creating Strong Passwords'),
                    'description' => 'Discover the importance of strong, unique passwords for protecting your online accounts. This course provides best practices for creating and managing passwords that are difficult to crack.',
                    'pass_score' => 80,
                    'is_active' => true,
                ],
                [
                    'title' => 'Safe Browsing Habits',
                    'slug' => Str::slug('Safe Browsing Habits'),
                    'description' => 'Browse the web with confidence. This course teaches you how to identify and avoid online threats, including malware, malicious websites, and dangerous downloads. Learn how to protect your personal information while online.',
                    'pass_score' => 80,
                    'is_active' => true,
                ],
            ];

            foreach ($courses as $courseData) {
                Module::firstOrCreate(
                    ['slug' => $courseData['slug']],
                    array_merge($courseData, ['user_id' => $lecturer->id])
                );
            }
        }
    }
}