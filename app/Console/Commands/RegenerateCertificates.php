<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Console\Command;

class RegenerateCertificates extends Command
{
    protected $signature = 'certificates:regenerate
        {--chunk=50 : Number of certificates processed per chunk}';

    protected $description = 'Re-render all certificates using the latest template and numbering scheme.';

    public function handle(CertificateService $service): int
    {
        $this->info('Regenerating certificates with latest template…');

        $total = Certificate::count();
        if ($total === 0) {
            $this->info('No certificates found.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $chunk = max(1, (int) $this->option('chunk'));

        Certificate::with(['module', 'user', 'attempt'])
            ->orderBy('id')
            ->chunk($chunk, function ($certificates) use ($service, $bar) {
                foreach ($certificates as $certificate) {
                    try {
                        if ($certificate->module && $certificate->user) {
                            $service->regenerateCertificate($certificate);
                        } else {
                            $this->warn("Skipping certificate {$certificate->id} (missing relationships).");
                        }
                    } catch (\Throwable $e) {
                        $this->error("Failed to regenerate certificate {$certificate->id}: {$e->getMessage()}");
                    }

                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine(2);
        $this->info('All certificates regenerated.');

        return self::SUCCESS;
    }
}
