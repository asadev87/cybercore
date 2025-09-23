<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    public function index()
    {
        // cache for 5 minutes to avoid heavy recompute
        $rows = Cache::remember('leaderboard:alltime', 300, function () {
            return QuizAttempt::query()
                ->whereNotNull('completed_at')
                ->selectRaw('user_id, AVG(score) as avg_score, COUNT(*) as attempts')
                ->groupBy('user_id')
                ->orderByDesc('avg_score')
                ->limit(50)
                ->with('user:id,name') // eager load names
                ->get();
        });

        return view('leaderboard.index', ['rows' => $rows]);
    }
}
