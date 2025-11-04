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
        $now = Carbon::now()->startOfDay();
        $days7Start = $now->copy()->subDays(6);   // inclusive -> 7 total days
        $days30Start = $now->copy()->subDays(29); // inclusive -> 30 total days
        $period7  = CarbonPeriod::create($days7Start, '1 day', $now);
        $period30 = CarbonPeriod::create($days30Start, '1 day', $now);

        $logins = UserLogin::where('user_id', $userId)
            ->whereNotNull('logged_in_at')
            ->where('logged_in_at', '>=', $days30Start->copy()->startOfDay())
            ->orderBy('logged_in_at', 'asc')
            ->get();

        $attempts = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'asc')
            ->get();

        $attemptModuleIds = $attempts->pluck('module_id')->filter()->unique();
        $moduleTitles = $attemptModuleIds->isNotEmpty()
            ? Module::whereIn('id', $attemptModuleIds)->pluck('title', 'id')
            : collect();

        $moduleScoreLabels = [];
        $moduleScoreAverages = [];
        foreach ($attempts->groupBy('module_id') as $moduleId => $moduleAttempts) {
            if ($moduleId === null || $moduleAttempts->isEmpty()) {
                continue;
            }

            $title = $moduleTitles[$moduleId] ?? optional($moduleAttempts->first()->module)->title ?? __('Unknown module');
            $moduleScoreLabels[] = $title;
            $moduleScoreAverages[] = round($moduleAttempts->avg('score'), 1);
        }

        $overallAverageScore = $attempts->avg('score');

        // 7-day login series
        $login7Labels = [];
        $login7Counts = [];
        foreach ($period7 as $day) {
            $start = $day->copy()->startOfDay();
            $end   = $day->copy()->endOfDay();
            $login7Labels[] = $start->format('Y-m-d');
            $login7Counts[] = $logins->whereBetween('logged_in_at', [$start, $end])->count();
        }

        // 30-day login series
        $login30Labels = [];
        $login30Counts = [];
        foreach ($period30 as $day) {
            $start = $day->copy()->startOfDay();
            $end   = $day->copy()->endOfDay();
            $login30Labels[] = $start->format('Y-m-d');
            $login30Counts[] = $logins->whereBetween('logged_in_at', [$start, $end])->count();
        }

        // Recent attempts + module statuses
        $recent = $attempts->sortByDesc('completed_at')->take(10);
        $modules = Module::where('is_active', true)->orderBy('title')->get();
        $progress = UserProgress::where('user_id', $userId)->pluck('percent_complete','module_id');

        $totalAttempts = $attempts->count();

        return view('performance.index', compact(
            'login7Labels','login7Counts',
            'login30Labels','login30Counts',
            'moduleScoreLabels','moduleScoreAverages','overallAverageScore',
            'recent','modules','progress','totalAttempts'
        ));
    }
}
