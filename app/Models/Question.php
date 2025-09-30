<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Question extends Model
{
    protected $fillable = [
        'module_id','section_id','type','difficulty','stem',
        'options','answer','is_active', 'explanation',
    ];

    // If DB stores JSON/TEXT, Laravel will cast to array automatically
    protected $casts = [
        'options' => 'array',
        'answer'  => 'array',
    ];

    // Optional: default to [] instead of null for robustness
    protected function options(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($v) => $v ?? []
        );
    }
    protected function answer(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($v) => $v ?? []
        );
    }
}
