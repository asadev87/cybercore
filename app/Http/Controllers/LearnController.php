<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;

class LearnController extends Controller
{
    public function index()
    {
        $modules = Module::where('is_active', true)
            ->withAvg('questions', 'difficulty')
            ->orderBy('title')
            ->get();

        // progress map: module_id => percent
        $progress = UserProgress::where('user_id', Auth::id())
            ->pluck('percent_complete', 'module_id');

        $difficulty = $modules->mapWithKeys(function (Module $module) {
            $avg = (float) ($module->questions_avg_difficulty ?? 0);
            $rounded = round($avg, 1);

            if ($avg >= 3.5) {
                $label = 'Advanced';
                $pill = 'bg-rose-100 text-rose-800 dark:bg-rose-500/15 dark:text-rose-200';
            } elseif ($avg >= 2.5) {
                $label = 'Intermediate';
                $pill = 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-200';
            } elseif ($avg > 0) {
                $label = 'Beginner';
                $pill = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200';
            } else {
                $label = 'Unrated';
                $pill = 'bg-muted text-muted-foreground';
            }

            return [
                $module->id => [
                    'label'   => $label,
                    'average' => $rounded,
                    'pill'    => $pill,
                ],
            ];
        });

        return view('learn.index', [
            'modules'     => $modules,
            'progress'    => $progress,
            'difficulty'  => $difficulty,
        ]);
    }

    public function show(\App\Models\Module $module)
{
    $userId = auth()->id();
    $sections = $module->sections()->withCount(['questions'])->get();

    // join simple progress
    $prog = \DB::table('user_section_progress')->where('user_id',$userId)->pluck('percent_complete','section_id');

    return view('learn.module', compact('module','sections','prog'));
}

}
