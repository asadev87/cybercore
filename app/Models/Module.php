<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Module extends Model
{
    protected $fillable = ['slug', 'title', 'description', 'pass_score', 'is_active', 'user_id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function sections()
    {
        return $this->hasMany(\App\Models\Section::class)->orderBy('order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}