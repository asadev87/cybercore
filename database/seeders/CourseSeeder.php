<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\User;
use App\Models\Section;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

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
                    'slug' => 'phishing-awareness',
                    'description' => 'Learn how to recognize and avoid phishing scams. This course covers common phishing techniques, how to spot fake emails and websites, and what to do if you suspect you\'ve been targeted.',
                    'note' => 'Bring along two recent messages that felt “off” so you can apply the red-flag checklist while you learn.',
                    'pass_score' => 80,
                    'is_active' => true,
                ],
                [
                    'title' => 'Creating Strong Passwords',
                    'slug' => 'creating-strong-passwords',
                    'description' => 'Discover the importance of strong, unique passwords for protecting your online accounts. This course provides best practices for creating and managing passwords that are difficult to crack.',
                    'note' => 'Have your password manager (or a list of accounts to upgrade) ready so you can craft stronger credentials on the spot.',
                    'pass_score' => 80,
                    'is_active' => true,
                ],
                [
                    'title' => 'Safe Browsing Habits',
                    'slug' => 'safe-browsing-habits',
                    'description' => 'Browse the web with confidence. This course teaches you how to identify and avoid online threats, including malware, malicious websites, and dangerous downloads. Learn how to protect your personal information while online.',
                    'note' => 'Write down the networks and browsers you use most so the security tips map directly to your daily routines.',
                    'pass_score' => 80,
                    'is_active' => true,
                ],
            ];

            foreach ($courses as $courseData) {
                Module::updateOrCreate(
                    ['slug' => $courseData['slug']],
                    array_merge($courseData, ['user_id' => $lecturer->id])
                );
            }

            $this->deduplicateLegacySlugs();
        }
    }

    private function deduplicateLegacySlugs(): void
    {
        $map = [
            'cybercore-phish-survival-101' => 'phishing-awareness',
            'cybercore-phish-survival-101-legacy' => 'phishing-awareness',
            'cybercore-password-forge' => 'creating-strong-passwords',
            'cybercore-password-forge-legacy' => 'creating-strong-passwords',
            'cybercore-safe-browsing-badge' => 'safe-browsing-habits',
            'cybercore-safe-browsing-badge-legacy' => 'safe-browsing-habits',
        ];

        foreach ($map as $legacySlug => $canonicalSlug) {
            $legacy = Module::where('slug', $legacySlug)->first();
            if (!$legacy) {
                continue;
            }

            $canonical = Module::where('slug', $canonicalSlug)->first();

            if (!$canonical) {
                $legacy->slug = $canonicalSlug;
                $legacy->save();
                continue;
            }

            if ($legacy->id === $canonical->id) {
                continue;
            }

            DB::transaction(function () use ($legacy, $canonical) {
                Section::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);
                Question::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);
                QuizAttempt::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);
                $this->mergeProgress($legacy->id, $canonical->id);
                Certificate::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);

                if ($canonical->note === null && $legacy->note !== null) {
                    $canonical->note = $legacy->note;
                    $canonical->save();
                }

                $legacy->delete();
            });
        }
    }

    private function mergeProgress(int $legacyId, int $canonicalId): void
    {
        $entries = UserProgress::where('module_id', $legacyId)->get();

        foreach ($entries as $legacyProgress) {
            $existing = UserProgress::where('module_id', $canonicalId)
                ->where('user_id', $legacyProgress->user_id)
                ->first();

            if ($existing) {
                $existing->percent_complete = max(
                    (int) $existing->percent_complete,
                    (int) $legacyProgress->percent_complete
                );

                if ($legacyProgress->status === 'completed' || $existing->status === 'completed') {
                    $existing->status = 'completed';
                } elseif ($legacyProgress->status === 'in_progress' || $existing->status === 'in_progress') {
                    $existing->status = 'in_progress';
                } else {
                    $existing->status = $existing->status ?? $legacyProgress->status;
                }

                $existing->last_activity_at = $this->maxDate(
                    $existing->last_activity_at,
                    $legacyProgress->last_activity_at
                );

                $existing->save();
                $legacyProgress->delete();
            } else {
                $legacyProgress->module_id = $canonicalId;
                $legacyProgress->save();
            }
        }
    }

    private function maxDate($first, $second)
    {
        if (!$first) {
            return $second;
        }

        if (!$second) {
            return $first;
        }

        return $first > $second ? $first : $second;
    }
}
