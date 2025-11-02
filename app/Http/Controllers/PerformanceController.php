<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
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
        $days7  = CarbonPeriod::create($days7Start, '1 day', $now);
        $days30 = CarbonPeriod::create($days30Start, '1 day', $now);

        $attempts = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'asc')
            ->get();

        // 7-day series (formerly weekly)
        $weeklyLabels = [];
        $weeklyAvg = [];
        $weeklyCount = [];
        foreach ($days7 as $d) {
            $start = $d->copy()->startOfDay();
            $end   = $d->copy()->endOfDay();
            $bucket = $attempts->whereBetween('completed_at', [$start, $end]);
            $weeklyLabels[] = $start->format('Y-m-d');               // Chart.js time scale x (day)
            $weeklyCount[]  = $bucket->count();
            $weeklyAvg[]    = $bucket->avg('score') ? round($bucket->avg('score'), 1) : null;
        }

        // 30-day series (formerly monthly)
        $monthlyLabels = [];
        $monthlyAvg = [];
        $monthlyCount = [];
        foreach ($days30 as $d) {
            $start = $d->copy()->startOfDay();
            $end   = $d->copy()->endOfDay();
            $bucket = $attempts->whereBetween('completed_at', [$start, $end]);
            $monthlyLabels[] = $start->format('Y-m-d');
            $monthlyCount[]  = $bucket->count();
            $monthlyAvg[]    = $bucket->avg('score') ? round($bucket->avg('score'), 1) : null;
        }

        // Recent attempts + module statuses
        $recent = $attempts->sortByDesc('completed_at')->take(10);
        $modules = Module::where('is_active', true)->orderBy('title')->get();
        $progress = UserProgress::where('user_id', $userId)->pluck('percent_complete','module_id');

        $totalAttempts = $attempts->count();

        return view('performance.index', compact(
            'weeklyLabels','weeklyAvg','weeklyCount',
            'monthlyLabels','monthlyAvg','monthlyCount',
            'recent','modules','progress','totalAttempts'
        ));
    }
}
