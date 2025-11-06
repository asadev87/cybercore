<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LecturerPerformanceController extends Controller
{
    public function index(Request $request): View
    {
        $lecturerId = Auth::id();

        $modules = Module::query()
            ->select('id', 'title', 'slug', 'pass_score')
            ->where('user_id', $lecturerId)
            ->orderBy('title')
            ->get();

        $moduleIds = $modules->pluck('id');
        $attemptsByModule = collect();
        $summaryByModule = collect();

        $totals = [
            'completions'   => 0,
            'average_score' => null,
            'pass_rate'     => null,
        ];

        if ($moduleIds->isNotEmpty()) {
            $attempts = QuizAttempt::query()
                ->with('user:id,name,email')
                ->whereIn('module_id', $moduleIds)
                ->whereNotNull('completed_at')
                ->orderByDesc('score')
                ->orderByDesc('completed_at')
                ->get();

            $bestAttempts = $attempts->unique(fn (QuizAttempt $attempt) => $attempt->module_id . ':' . $attempt->user_id);

            $attemptsByModule = $bestAttempts
                ->groupBy('module_id')
                ->map(fn (Collection $group) => $group->sortByDesc('score')->values());

            $passScores = $modules->pluck('pass_score', 'id');

            $summaryByModule = $modules->mapWithKeys(function (Module $module) use ($attemptsByModule, $passScores) {
                /** @var \Illuminate\Support\Collection $rows */
                $rows = $attemptsByModule->get($module->id, collect());

                if ($rows->isEmpty()) {
                    return [$module->id => [
                        'completions'   => 0,
                        'average_score' => null,
                        'pass_rate'     => null,
                    ]];
                }

                $count = $rows->count();
                $average = round($rows->avg('score') ?? 0, 1);
                $threshold = (int) ($passScores[$module->id] ?? 70);
                $passCount = $rows->filter(fn (QuizAttempt $attempt) => (int) $attempt->score >= $threshold)->count();

                return [$module->id => [
                    'completions'   => $count,
                    'average_score' => $average,
                    'pass_rate'     => round(($passCount / max(1, $count)) * 100, 1),
                ]];
            });

            $allRows = $attemptsByModule->collapse();

            if ($allRows->isNotEmpty()) {
                $totals['completions'] = $allRows->count();
                $totals['average_score'] = round($allRows->avg('score') ?? 0, 1);

                $passed = $allRows->filter(function (QuizAttempt $attempt) use ($passScores) {
                    $threshold = (int) ($passScores[$attempt->module_id] ?? 70);
                    return (int) $attempt->score >= $threshold;
                })->count();

                $totals['pass_rate'] = round(($passed / max(1, $allRows->count())) * 100, 1);
            }
        }

        return view('admin.performance.lecturer', [
            'modules'         => $modules,
            'attemptsByModule'=> $attemptsByModule,
            'summaryByModule' => $summaryByModule,
            'totals'          => $totals,
        ]);
    }
}

