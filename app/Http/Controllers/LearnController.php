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

            if ($avg >= 3.5) {
                $label = 'Advanced';
            } elseif ($avg >= 2.5) {
                $label = 'Intermediate';
            } elseif ($avg > 0) {
                $label = 'Beginner';
            } else {
                $label = null;
            }

            return [$module->id => $label];
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
