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
    ];

    // If DB stores JSON/TEXT, Laravel will cast to array automatically
    protected $casts = [
        'choices' => 'array',
        'options' => 'array',
        'answer'  => 'array',
    ];

    // Optional: default to [] instead of null for robustness
    protected function choices(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes) {
                if (is_array($value)) {
                    return array_values($value);
                }
                $fallback = $attributes['options'] ?? null;
                return is_array($fallback) ? array_values($fallback) : [];
            }
        );
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes) {
                if (is_array($value)) {
                    return array_values($value);
                }
                $fallback = $attributes['choices'] ?? null;
                return is_array($fallback) ? array_values($fallback) : [];
            }
        );
    }

    protected function answer(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? []
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


}
