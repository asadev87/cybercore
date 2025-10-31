<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\Section;
use App\Models\UserProgress;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeduplicateModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:dedupe {--dry-run : Show the changes that would be applied without mutating data}';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Merge duplicate module records created under legacy slugs.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $map = $this->duplicateSlugMap();
        $affected = 0;

        foreach ($map as $legacySlug => $canonicalSlug) {
            $legacy = Module::where('slug', $legacySlug)->first();
            if (!$legacy) {
                continue;
            }

            $canonical = Module::where('slug', $canonicalSlug)->first();

            if (!$canonical) {
                if ($dryRun) {
                    $this->line(sprintf('[dry-run] Would rename "%s" to "%s" (canonical record missing).', $legacySlug, $canonicalSlug));
                } else {
                    $legacy->slug = $canonicalSlug;
                    $legacy->save();
                    $this->info(sprintf('Renamed "%s" to "%s" (no existing canonical record).', $legacySlug, $canonicalSlug));
                }
                $affected++;
                continue;
            }

            if ($legacy->id === $canonical->id) {
                continue;
            }

            $counts = [
                'sections'     => Section::where('module_id', $legacy->id)->count(),
                'questions'    => Question::where('module_id', $legacy->id)->count(),
                'quizAttempts' => QuizAttempt::where('module_id', $legacy->id)->count(),
                'progress'     => UserProgress::where('module_id', $legacy->id)->count(),
                'certificates' => Certificate::where('module_id', $legacy->id)->count(),
            ];

            if ($dryRun) {
                $this->line(sprintf(
                    '[dry-run] Would merge "%s" into "%s" (sections: %d, questions: %d, attempts: %d, progress: %d, certificates: %d).',
                    $legacySlug,
                    $canonicalSlug,
                    $counts['sections'],
                    $counts['questions'],
                    $counts['quizAttempts'],
                    $counts['progress'],
                    $counts['certificates']
                ));
                $affected++;
                continue;
            }

            DB::transaction(function () use ($legacy, $canonical) {
                Section::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);
                Question::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);
                QuizAttempt::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);
                $this->mergeProgress($legacy->id, $canonical->id);
                Certificate::where('module_id', $legacy->id)->update(['module_id' => $canonical->id]);

                $legacy->delete();
            });

            $this->info(sprintf('Merged "%s" into "%s".', $legacySlug, $canonicalSlug));
            $affected++;
        }

        if ($affected === 0) {
            $this->info('No legacy module slugs were found.');
        }

        return self::SUCCESS;
    }

    private function duplicateSlugMap(): array
    {
        return [
            'cybercore-phish-survival-101' => 'phishing-awareness',
            'cybercore-phish-survival-101-legacy' => 'phishing-awareness',
            'cybercore-password-forge' => 'creating-strong-passwords',
            'cybercore-password-forge-legacy' => 'creating-strong-passwords',
            'cybercore-safe-browsing-badge' => 'safe-browsing-habits',
            'cybercore-safe-browsing-badge-legacy' => 'safe-browsing-habits',
        ];
    }

    private function mergeProgress(int $legacyId, int $canonicalId): void
    {
        $entries = UserProgress::where('module_id', $legacyId)->get();

        foreach ($entries as $legacyProgress) {
            $existing = UserProgress::where('module_id', $canonicalId)
                ->where('user_id', $legacyProgress->user_id)
                ->first();

            if ($existing) {
                $existing->percent_complete = max(
                    (int) $existing->percent_complete,
                    (int) $legacyProgress->percent_complete
                );

                if ($legacyProgress->status === 'completed' || $existing->status === 'completed') {
                    $existing->status = 'completed';
                } elseif ($legacyProgress->status === 'in_progress' || $existing->status === 'in_progress') {
                    $existing->status = 'in_progress';
                } else {
                    $existing->status = $existing->status ?? $legacyProgress->status;
                }

                $existing->last_activity_at = $this->maxDate(
                    $existing->last_activity_at,
                    $legacyProgress->last_activity_at
                );

                $existing->save();
                $legacyProgress->delete();
            } else {
                $legacyProgress->module_id = $canonicalId;
                $legacyProgress->save();
            }
        }
    }

    private function maxDate($first, $second)
    {
        if (!$first) {
            return $second;
        }

        if (!$second) {
            return $first;
        }

        return $first > $second ? $first : $second;
    }
}
