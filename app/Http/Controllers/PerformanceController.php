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

        // Last 12 weeks + 12 months windows
        $now = Carbon::now();
        $weeks  = CarbonPeriod::create($now->copy()->startOfWeek(), '1 week', $now->copy()->startOfWeek()->addWeeks(11));
        $months = CarbonPeriod::create($now->copy()->startOfMonth()->subMonths(11), '1 month', $now->copy()->startOfMonth());

        $attempts = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'asc')
            ->get();

        // Weekly series
        $weeklyLabels = [];
        $weeklyAvg = [];
        $weeklyCount = [];
        foreach ($weeks as $w) {
            $start = $w->copy();
            $end   = $w->copy()->endOfWeek();
            $bucket = $attempts->whereBetween('completed_at', [$start, $end]);
            $weeklyLabels[] = $start->format('Y-m-d');               // Chart.js time scale x
            $weeklyCount[]  = $bucket->count();
            $weeklyAvg[]    = $bucket->avg('score') ? round($bucket->avg('score'), 1) : null;
        }

        // Monthly series
        $monthlyLabels = [];
        $monthlyAvg = [];
        $monthlyCount = [];
        foreach ($months as $m) {
            $start = $m->copy()->startOfMonth();
            $end   = $m->copy()->endOfMonth();
            $bucket = $attempts->whereBetween('completed_at', [$start, $end]);
            $monthlyLabels[] = $start->format('Y-m-d');
            $monthlyCount[]  = $bucket->count();
            $monthlyAvg[]    = $bucket->avg('score') ? round($bucket->avg('score'), 1) : null;
        }

        // Recent attempts + module statuses
        $recent = $attempts->sortByDesc('completed_at')->take(10);
        $modules = Module::where('is_active', true)->orderBy('title')->get();
        $progress = UserProgress::where('user_id', $userId)->pluck('percent_complete','module_id');

        return view('performance.index', compact(
            'weeklyLabels','weeklyAvg','weeklyCount',
            'monthlyLabels','monthlyAvg','monthlyCount',
            'recent','modules','progress'
        ));
    }
}
