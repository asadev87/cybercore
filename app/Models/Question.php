<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Question extends Model
{
    protected $fillable = [
        'module_id','section_id','type','difficulty','stem',
        'choices','answer','is_active',
    ];

    // If DB stores JSON/TEXT, Laravel will cast to array automatically
    protected $casts = [
        'choices' => 'array',
        'answer'  => 'array',
    ];

    // Optional: default to [] instead of null for robustness
    protected function choices(): \Illuminate\Database\Eloquent\Casts\Attribute
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
