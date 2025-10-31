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

        $userId = $user->id;

        // Module completion milestones
        $completedCount = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        if ($completedCount >= 1) {
            $this->award($userId, 'first-completion');
        }

        if ($completedCount >= 3) {
            $this->award($userId, 'completion-streak-3');
        }

        if ($completedCount >= 5) {
            $this->award($userId, 'completion-mastery-5');
        }

        // High score achievements
        if ($score >= 90) {
            $this->award($userId, 'high-scorer-90');
        }

        if ($score === 100) {
            $this->award($userId, 'perfect-score');
        }

        $highScoreCount = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->where('score', '>=', 90)
            ->count();

        if ($highScoreCount >= 3) {
            $this->award($userId, 'high-scorer-streak-3');
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
