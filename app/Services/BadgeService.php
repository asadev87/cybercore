<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\QuizAttempt;

class BadgeService
{
    public function checkAndAward(QuizAttempt $attempt): void
    {
        $user = $attempt->user;
        $score = (int)$attempt->score;

        // First completion
        $firstCompletion = !$user->userBadges()->exists()
            && $user->progress()->where('status','completed')->exists();

        if ($firstCompletion) {
            $this->award($user, 'first-completion');
        }

        // High Scorer (>=90 on any attempt)
        if ($score >= 90) {
            $this->award($user, 'high-scorer-90');
        }
    }

    private function award($user, string $slug): void
    {
        $badge = Badge::where('slug',$slug)->where('is_active',true)->first();
        if (!$badge) return;

        $user->badges()->syncWithoutDetaching([
            $badge->id => ['awarded_at' => now()]
        ]);
    }
    public function userBadges() { return $this->hasMany(\Illuminate\Database\Eloquent\Relations\Pivot::class, 'user_id'); }
public function progress()   { return $this->hasMany(\App\Models\UserProgress::class); }

}
