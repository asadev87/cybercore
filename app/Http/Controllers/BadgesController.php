<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class BadgesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Completed modules from UserProgress (status or percent==100)
        $completed = \App\Models\UserProgress::with('module:id,title,slug,description')
            ->where('user_id', $user->id)
            ->where(function($q){
                $q->where('status', 'completed')->orWhere('percent_complete', 100);
            })
            ->orderByDesc('last_activity_at')
            ->get();

        // Optional: show earned badges if your tables/services exist
       $hasBadgeTables = Schema::hasTable('badges') && Schema::hasTable('user_badges');

        $earnedBadges = collect();
        if ($hasBadgeTables && method_exists($user, 'badges')) {
            $earnedBadges = $user->badges()->withPivot('awarded_at')->orderBy('user_badges.awarded_at','desc')->get();
        }

        return view('badges.index', compact('completed', 'earnedBadges', 'hasBadgeTables'));
    }
}
