<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminPerformanceController extends Controller
{
    public function index(): View
    {
        $now = now();
        $thirtyDaysAgo = $now->clone()->subDays(30);
        $sevenDaysAgo = $now->clone()->subDays(7);
        $fourteenDaysAgo = $now->clone()->subDays(14);

        $attemptsQuery = QuizAttempt::query()->whereNotNull('completed_at');

        $totalAttempts = (clone $attemptsQuery)->count();
        $attemptsLast30 = (clone $attemptsQuery)->where('completed_at', '>=', $thirtyDaysAgo)->count();
        $avgScore = round((clone $attemptsQuery)->avg('score') ?? 0, 1);

        $passCount = (clone $attemptsQuery)
            ->join('modules as m', 'm.id', '=', 'quiz_attempts.module_id')
            ->whereRaw('quiz_attempts.score >= COALESCE(m.pass_score, 70)')
            ->count();

        $passRate = $totalAttempts > 0 ? round(($passCount / $totalAttempts) * 100, 1) : 0;

        $activeLearners = User::whereHas('logins', function ($query) use ($sevenDaysAgo) {
            $query->where('logged_in_at', '>=', $sevenDaysAgo);
        })->count();

        $totalLearners = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'lecturer']);
        })->count();

        $loginTrend = collect(range(0, 13))->map(function ($offset) use ($now) {
            $dayStart = $now->clone()->subDays(13 - $offset)->startOfDay();
            $dayEnd = $dayStart->clone()->endOfDay();

            $count = UserLogin::whereBetween('logged_in_at', [$dayStart, $dayEnd])->count();

            return [
                'date' => $dayStart->toDateString(),
                'total' => $count,
            ];
        });

        $scoreBandsRow = (clone $attemptsQuery)
            ->selectRaw('
                SUM(CASE WHEN score < 50 THEN 1 ELSE 0 END) as under_50,
                SUM(CASE WHEN score BETWEEN 50 AND 69 THEN 1 ELSE 0 END) as between_50_69,
                SUM(CASE WHEN score BETWEEN 70 AND 84 THEN 1 ELSE 0 END) as between_70_84,
                SUM(CASE WHEN score >= 85 THEN 1 ELSE 0 END) as over_85
            ')
            ->first();

        $scoreDistribution = [
            [
                'label' => '85%+',
                'count' => (int) optional($scoreBandsRow)->over_85,
            ],
            [
                'label' => '70–84%',
                'count' => (int) optional($scoreBandsRow)->between_70_84,
            ],
            [
                'label' => '50–69%',
                'count' => (int) optional($scoreBandsRow)->between_50_69,
            ],
            [
                'label' => '< 50%',
                'count' => (int) optional($scoreBandsRow)->under_50,
            ],
        ];

        $topModules = QuizAttempt::query()
            ->select([
                'modules.id',
                'modules.title',
                DB::raw('COUNT(quiz_attempts.id) as attempts'),
                DB::raw('AVG(quiz_attempts.score) as average_score'),
                DB::raw('SUM(CASE WHEN quiz_attempts.score >= COALESCE(modules.pass_score, 70) THEN 1 ELSE 0 END) as passes'),
            ])
            ->join('modules', 'modules.id', '=', 'quiz_attempts.module_id')
            ->whereNotNull('quiz_attempts.completed_at')
            ->groupBy('modules.id', 'modules.title')
            ->orderByDesc('attempts')
            ->limit(6)
            ->get();

        $recentAttempts = QuizAttempt::query()
            ->with(['user:id,name,email', 'module:id,title,pass_score'])
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->limit(12)
            ->get();

        $recentLogins = UserLogin::query()
            ->with('user:id,name,email')
            ->latest('logged_in_at')
            ->limit(12)
            ->get();

        return view('admin.performance.index', [
            'overview' => [
                'total_attempts' => $totalAttempts,
                'attempts_last_30' => $attemptsLast30,
                'avg_score' => $avgScore,
                'pass_rate' => $passRate,
                'active_learners' => $activeLearners,
                'total_learners' => $totalLearners,
            ],
            'loginTrend' => $loginTrend,
            'scoreDistribution' => $scoreDistribution,
            'topModules' => $topModules,
            'recentAttempts' => $recentAttempts,
            'recentLogins' => $recentLogins,
        ]);
    }
}
