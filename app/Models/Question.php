<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Question extends Model
{
    protected $fillable = [
        'module_id',
        'section_id',
        'type',
        'difficulty',
        'stem',
        'choices',
        'options',
        'answer',
        'is_active',
        'explanation',
        'notes',
    ];

    // If DB stores JSON/TEXT, Laravel will cast to array automatically
    protected $casts = [
        'choices' => 'array',
        'options' => 'array',
        'answer'  => 'array',
        'notes'   => 'array',
    ];

    // Optional: default to [] instead of null for robustness
    protected function choices(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes) {
                $resolved = $this->normalizeJsonValue($value);
                if ($resolved !== null) {
                    return $resolved;
                }

                $fallback = $this->normalizeJsonValue($attributes['options'] ?? null);
                return $fallback ?? [];
            }
        );
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes) {
                $resolved = $this->normalizeJsonValue($value);
                if ($resolved !== null) {
                    return $resolved;
                }

                $fallback = $this->normalizeJsonValue($attributes['choices'] ?? null);
                return $fallback ?? [];
            }
        );
    }

    protected function answer(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeJsonValue($value) ?? []
        );
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }


    private function normalizeJsonValue($value): ?array
    {
        if (is_array($value)) {
            return array_values($value);
        }

        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values($decoded);
            }

            // Fallback: treat plain strings as single-value arrays
            $trimmed = trim($value);
            if ($trimmed !== '') {
                return [$trimmed];
            }
        }

        return null;
    }
}
