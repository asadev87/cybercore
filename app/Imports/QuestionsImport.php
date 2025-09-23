<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class QuestionsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function __construct(private int $moduleId) {}

    public function model(array $row)
    {
        // CSV/XLSX headings expected (case-insensitive):
        // type, stem, options, answer, explanation, difficulty, is_active
        $type  = strtolower(trim($row['type'] ?? 'mcq'));

        // split "a || b || c" into array (or accept JSON)
        $opt = $row['options'] ?? null;
        $ans = $row['answer']  ?? null;

        $options = $this->toArray($opt);
        $answer  = $this->toArray($ans);

        return new Question([
            'module_id'   => $this->moduleId,
            'type'        => in_array($type, ['mcq','truefalse','fib'], true) ? $type : 'mcq',
            'stem'        => trim((string)($row['stem'] ?? '')),
            'options'     => $options,
            'answer'      => $answer,
            'explanation' => trim((string)($row['explanation'] ?? '')),
            'difficulty'  => (int)($row['difficulty'] ?? 2),
            'is_active'   => filter_var($row['is_active'] ?? true, FILTER_VALIDATE_BOOL),
        ]);
    }

    public function rules(): array
    {
        // WithHeadingRow lets you validate by column name. :contentReference[oaicite:1]{index=1}
        return [
            '*.type'   => ['required','in:mcq,truefalse,fib'],
            '*.stem'   => ['required','string','max:2000'],
            '*.answer' => ['required'], // we also parse to array
            '*.difficulty' => ['nullable','integer','between:1,5'],
        ];
    }

    private function toArray($value): array
    {
        $v = trim((string)($value ?? ''));
        if ($v === '') return [];
        // accept JSON array
        if (Str::startsWith($v, '[')) {
            return array_values(array_map('strval', json_decode($v, true) ?? []));
        }
        // support "A || B || C"
        return array_values(array_map(fn($s) => trim((string)$s), preg_split('/\s*\|\|\s*/', $v)));
    }
}
