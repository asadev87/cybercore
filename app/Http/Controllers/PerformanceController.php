<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\UserLogin;
use App\Models\UserProgress;
use App\Models\Module;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Windows: last 7 days (daily) and last 30 days (daily)
        $now         = Carbon::now()->startOfDay();
        $endOfDay    = $now->copy()->endOfDay();
        $days7Start  = $now->copy()->subDays(6);   // inclusive -> 7 total days
        $days30Start = $now->copy()->subDays(29);  // inclusive -> 30 total days

        $loginCounts = UserLogin::query()
            ->where('user_id', $userId)
            ->whereNotNull('logged_in_at')
            ->whereBetween('logged_in_at', [$days30Start, $endOfDay])
            ->selectRaw('DATE(logged_in_at) as login_date, COUNT(*) as total')
            ->groupBy('login_date')
            ->pluck('total', 'login_date');

        $buildSeries = function (CarbonPeriod $period) use ($loginCounts): array {
            $labels = [];
            $counts = [];

            foreach ($period as $day) {
                $key      = $day->toDateString();
                $labels[] = $key;
                $counts[] = (int) ($loginCounts[$key] ?? 0);
            }

            return [$labels, $counts];
        };

        [$login7Labels, $login7Counts]   = $buildSeries(CarbonPeriod::create($days7Start, '1 day', $now));
        [$login30Labels, $login30Counts] = $buildSeries(CarbonPeriod::create($days30Start, '1 day', $now));

        $attemptQuery = QuizAttempt::query()
            ->where('user_id', $userId)
            ->whereNotNull('completed_at');

        $moduleStats = (clone $attemptQuery)
            ->selectRaw('module_id, AVG(score) as avg_score')
            ->groupBy('module_id')
            ->get();

        $summary = (clone $attemptQuery)
            ->selectRaw('COUNT(*) as total, AVG(score) as average')
            ->first();

        $overallAverageScore = $summary ? round((float) $summary->average, 1) : null;
        $totalAttempts       = (int) ($summary->total ?? 0);

        $moduleIds = $moduleStats->pluck('module_id')->filter()->unique();
        $moduleTitles = $moduleIds->isEmpty()
            ? collect()
            : Module::whereIn('id', $moduleIds)->pluck('title', 'id');

        $moduleScoreLabels   = [];
        $moduleScoreAverages = [];
        foreach ($moduleStats as $stat) {
            if (! $stat->module_id) {
                continue;
            }

            $title = $moduleTitles[$stat->module_id] ?? __('Unknown module');
            $moduleScoreLabels[]   = $title;
            $moduleScoreAverages[] = round((float) $stat->avg_score, 1);
        }

        $recent = (clone $attemptQuery)
            ->with('module:id,title,pass_score')
            ->orderByDesc('completed_at')
            ->take(10)
            ->get();

        $modules  = Module::where('is_active', true)->orderBy('title')->get();
        $progress = UserProgress::where('user_id', $userId)->pluck('percent_complete','module_id');

        return view('performance.index', compact(
            'login7Labels','login7Counts',
            'login30Labels','login30Counts',
            'moduleScoreLabels','moduleScoreAverages','overallAverageScore',
            'recent','modules','progress','totalAttempts'
        ));
    }
}
