<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\QuizAttempt;
use App\Models\UserProgress;

class BadgeService
{
    public function checkAndAward(QuizAttempt $attempt): void
    {
        $user  = $attempt->user;
        $score = (int) $attempt->score;

        if (!$user) {
            return;
        }

        // First completion badge
        $hasFirstBadge = $user->badges()
            ->where('badges.slug', 'first-completion')
            ->exists();

        $hasCompletedModule = UserProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();

        if (!$hasFirstBadge && $hasCompletedModule) {
            $this->award($user->id, 'first-completion');
        }

        // High scorer badge
        if ($score >= 90) {
            $this->award($user->id, 'high-scorer-90');
        }
    }

    private function award(int $userId, string $slug): void
    {
        $badge = Badge::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$badge) {
            return;
        }

        $badge->users()->syncWithoutDetaching([
            $userId => ['awarded_at' => now()],
        ]);
    }
}

