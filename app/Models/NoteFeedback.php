<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteFeedback extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
        'question_id',
        'context',
        'helpful',
        'source',
    ];

    protected $casts = [
        'helpful' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
