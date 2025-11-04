<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Question;
use App\Models\QuizAttempt;

class AdaptiveSelector
{
    /**
     * Choose the next unseen question for this attempt, biased by adaptive difficulty.
     */
    public function nextQuestion(QuizAttempt $attempt): ?Question
    {
        $module   = $attempt->module;
        $askedIds = $attempt->questionAttempts()->pluck('question_id')->all();

        $window = max(1, (int) config('quiz.adaptive_window', 4));
        $recent = $attempt->questionAttempts()
            ->with(['question:id,difficulty'])
            ->latest()
            ->take($window)
            ->get();

        // base difficulty: 2 (easy=1 .. hard=5)
        $target = 2;
        if ($recent->count() > 0) {
            $correct = $recent->where('is_correct', true)->count();
            $acc = $correct / max(1, $recent->count());
            if ($acc >= 0.8)      $target = min(5, $this->avgDifficulty($recent) + 1);
            elseif ($acc <= 0.5)  $target = max(1, $this->avgDifficulty($recent) - 1);
            else                   $target = $this->avgDifficulty($recent);
        }

        // try target difficulty first
        $q = $this->pick($module, $askedIds, $target);
        if ($q) return $q;

        // fallback: fan out to other difficulties
        for ($d = 1; $d <= 5; $d++) {
            if ($d === $target) continue;
            $alt = $this->pick($module, $askedIds, $d);
            if ($alt) return $alt;
        }
        return null; // no more questions
    }

    private function avgDifficulty($attempts): int
    {
        $d = $attempts->map(fn($qa) => (int) optional($qa->question)->difficulty)->filter()->avg();
        return (int) round($d ?: 2);
    }

    private function pick(Module $module, array $askedIds, int $difficulty): ?Question
    {
        return $module->questions()
            ->where('is_active', true)
            ->where('difficulty', $difficulty)
            ->when($askedIds, fn($q) => $q->whereNotIn('id', $askedIds))
            ->inRandomOrder()
            ->first();
    }
}
