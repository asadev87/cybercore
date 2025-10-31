<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LearnController extends Controller
{
    public function index(Request $request)
    {
        $allModules = Module::where('is_active', true)
            ->withAvg('questions', 'difficulty')
            ->orderBy('title')
            ->get();

        $progress = UserProgress::where('user_id', Auth::id())
            ->pluck('percent_complete', 'module_id');

        $difficultyLabels = $allModules->mapWithKeys(function (Module $module) {
            $avg = (float) ($module->questions_avg_difficulty ?? 0);

            if ($avg >= 3.5) {
                $label = 'Advanced';
            } elseif ($avg >= 2.5) {
                $label = 'Intermediate';
            } elseif ($avg > 0) {
                $label = 'Beginner';
            } else {
                $label = 'Unrated';
            }

            return [$module->id => $label];
        });

        $topicsMap   = (array) config('learn.topics', []);
        $rolesMap    = (array) config('learn.roles', []);
        $moduleMeta  = (array) config('learn.module_tags', []);
        $descriptions = (array) config('module_notes.descriptions', []);
        $defaultNotes = (array) config('module_notes.defaults', []);

        foreach ($allModules as $module) {
            $meta = $moduleMeta[$module->slug] ?? ['topics' => [], 'roles' => []];
            $module->setAttribute('topics', $meta['topics'] ?? []);
            $module->setAttribute('roles', $meta['roles'] ?? []);
            $module->setAttribute('difficulty_label', $difficultyLabels[$module->id] ?? 'Unrated');
            $module->setAttribute('catalog_copy', $descriptions[$module->slug] ?? $module->description);
            $module->setAttribute('default_note', $defaultNotes[$module->slug] ?? null);
        }

        $searchTerm = trim((string) $request->query('search', ''));
        $topicFilter = $request->query('topic');
        $roleFilter = $request->query('role');
        $difficultyFilter = $request->query('difficulty');

        if ($topicFilter && ! array_key_exists($topicFilter, $topicsMap)) {
            $topicFilter = null;
        }

        if ($roleFilter && ! array_key_exists($roleFilter, $rolesMap)) {
            $roleFilter = null;
        }

        $allowedDifficulties = ['Beginner', 'Intermediate', 'Advanced', 'Unrated'];
        if ($difficultyFilter && ! in_array($difficultyFilter, $allowedDifficulties, true)) {
            $difficultyFilter = null;
        }

        $moduleIds = $allModules->pluck('id');

        $latestAttempts = QuizAttempt::query()
            ->where('user_id', Auth::id())
            ->whereIn('module_id', $moduleIds)
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->get()
            ->unique('module_id')
            ->keyBy('module_id');

        $bestScores = QuizAttempt::query()
            ->where('user_id', Auth::id())
            ->whereIn('module_id', $moduleIds)
            ->whereNotNull('completed_at')
            ->selectRaw('module_id, MAX(score) as best_score')
            ->groupBy('module_id')
            ->pluck('best_score', 'module_id');

        $filteredModules = $allModules->filter(function (Module $module) use (
            $searchTerm,
            $topicFilter,
            $roleFilter,
            $difficultyFilter,
            $descriptions,
            $defaultNotes,
            $difficultyLabels
        ) {
            if ($topicFilter && ! in_array($topicFilter, (array) $module->getAttribute('topics'), true)) {
                return false;
            }

            if ($roleFilter && ! in_array($roleFilter, (array) $module->getAttribute('roles'), true)) {
                return false;
            }

            if ($difficultyFilter) {
                $label = $difficultyLabels[$module->id] ?? 'Unrated';
                if ($label !== $difficultyFilter) {
                    return false;
                }
            }

            if ($searchTerm !== '') {
                $haystack = Str::lower(
                    implode(' ', [
                        $module->title,
                        $module->description,
                        $module->note,
                        $descriptions[$module->slug] ?? '',
                        $defaultNotes[$module->slug] ?? '',
                        implode(' ', (array) $module->getAttribute('topics')),
                        implode(' ', (array) $module->getAttribute('roles')),
                    ])
                );

                if (! Str::contains($haystack, Str::lower($searchTerm))) {
                    return false;
                }
            }

            return true;
        })->values();

        $totalStats = [
            'count'     => $allModules->count(),
            'completed' => $allModules->filter(fn (Module $module) => (int) ($progress[$module->id] ?? 0) >= 100)->count(),
        ];

        $filteredStats = [
            'count'     => $filteredModules->count(),
            'completed' => $filteredModules->filter(fn (Module $module) => (int) ($progress[$module->id] ?? 0) >= 100)->count(),
        ];

        return view('learn.index', [
            'modules'           => $filteredModules,
            'progress'          => $progress,
            'difficultyLabels'  => $difficultyLabels,
            'topicsMap'         => $topicsMap,
            'rolesMap'          => $rolesMap,
            'activeFilters'     => [
                'search'     => $searchTerm,
                'topic'      => $topicFilter,
                'role'       => $roleFilter,
                'difficulty' => $difficultyFilter,
            ],
            'totalStats'        => $totalStats,
            'filteredStats'     => $filteredStats,
            'latestAttempts'    => $latestAttempts,
            'bestScores'        => $bestScores,
        ]);
    }

    public function show(\App\Models\Module $module)
    {
        $userId = auth()->id();
        $sections = $module->sections()->withCount(['questions'])->get();

        $prog = \DB::table('user_section_progress')
            ->where('user_id', $userId)
            ->pluck('percent_complete', 'section_id');

        return view('learn.module', compact('module', 'sections', 'prog'));
    }
}
