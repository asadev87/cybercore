<?php

namespace App\Console\Commands;

use App\Models\Module;
use Illuminate\Console\Command;

class SyncModuleNotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:sync-notes {--dry-run : List modules that would be updated without saving changes}';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Synchronise standard prep notes for known training modules.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $noteMap = $this->buildNoteMap();
        if (empty($noteMap)) {
            $this->warn('No module notes configured to sync.');
            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');
        $updated = 0;

        $modules = Module::whereIn('slug', array_keys($noteMap))->get()->keyBy('slug');

        foreach ($noteMap as $slug => $note) {
            $module = $modules[$slug] ?? null;
            if (!$module) {
                $this->line(sprintf('Skipping "%s" — module not found.', $slug));
                continue;
            }

            $desired = trim((string) $note);
            if ($desired === '') {
                $this->line(sprintf('Skipping "%s" — desired note is empty.', $slug));
                continue;
            }

            $current = trim((string) $module->note);
            if ($current === $desired) {
                continue; // already up to date
            }

            if ($dryRun) {
                $this->line(sprintf('[dry-run] Would update "%s".', $slug));
            } else {
                $module->note = $desired;
                $module->save();
                $this->info(sprintf('Updated "%s".', $slug));
            }

            $updated++;
        }

        if ($updated === 0) {
            $this->info('Module notes already match the configured copy.');
        } elseif ($dryRun) {
            $this->info(sprintf('%d module(s) would be updated.', $updated));
        } else {
            $this->info(sprintf('Updated notes for %d module(s).', $updated));
        }

        return self::SUCCESS;
    }

    /**
     * Compile the slug => note map that should be enforced.
     */
    private function buildNoteMap(): array
    {
        $configNotes = (array) config('module_notes.defaults', []);

        $supplemental = [
            'phishing-awareness' => 'Bring along two recent messages that felt “off” so you can apply the red-flag checklist while you learn.',
            'creating-strong-passwords' => 'Have your password manager (or a list of accounts to upgrade) ready so you can craft stronger credentials on the spot.',
            'safe-browsing-habits' => 'Write down the networks and browsers you use most so the security tips map directly to your daily routines.',
        ];

        return array_filter($supplemental + $configNotes, fn ($note) => trim((string) $note) !== '');
    }
}
