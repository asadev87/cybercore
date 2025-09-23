<?php

namespace App\Exports;

use App\Models\QuizAttempt;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantScoresExport implements FromQuery, WithHeadings
{
    public function __construct(private $from, private $to, private ?int $moduleId = null) {}

    public function query()
    {
        return QuizAttempt::query()
            ->when($this->moduleId, fn ($q) => $q->where('module_id', $this->moduleId))
            ->whereBetween('quiz_attempts.created_at', [$this->from, $this->to])
            ->whereNotNull('completed_at')
            ->select([
                'quiz_attempts.id',
                'quiz_attempts.user_id',
                'quiz_attempts.module_id',
                'quiz_attempts.score',
                'quiz_attempts.completed_at',
            ]);
    }

    public function headings(): array
    {
        return ['Attempt ID', 'User ID', 'Module ID', 'Score', 'Completed At'];
    }
}
