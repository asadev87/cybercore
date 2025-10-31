<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'slug' => 'first-completion',
                'name' => 'First Completion',
                'icon' => '🎉',
                'description' => 'Complete your first learning module.',
                'criteria' => [
                    'type' => 'modules_completed',
                    'threshold' => 1,
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'high-scorer-90',
                'name' => 'High Scorer',
                'icon' => '🏅',
                'description' => 'Score 90% or higher on any module quiz.',
                'criteria' => [
                    'type' => 'single_score',
                    'threshold' => 90,
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'perfect-score',
                'name' => 'Perfect Score',
                'icon' => '💯',
                'description' => 'Earn a perfect 100% on any module quiz.',
                'criteria' => [
                    'type' => 'single_score',
                    'threshold' => 100,
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'completion-streak-3',
                'name' => 'Streak of Three',
                'icon' => '🔥',
                'description' => 'Complete three modules in total.',
                'criteria' => [
                    'type' => 'modules_completed',
                    'threshold' => 3,
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'completion-mastery-5',
                'name' => 'Learning Mastery',
                'icon' => '🧠',
                'description' => 'Complete five modules to show consistent learning.',
                'criteria' => [
                    'type' => 'modules_completed',
                    'threshold' => 5,
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'high-scorer-streak-3',
                'name' => 'Consistent High Scorer',
                'icon' => '🚀',
                'description' => 'Achieve scores of 90% or higher on three quizzes.',
                'criteria' => [
                    'type' => 'high_scores_count',
                    'threshold' => 3,
                ],
                'is_active' => true,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                Arr::except($badge, ['slug'])
            );
        }
    }
}
