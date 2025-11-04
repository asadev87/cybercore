<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionAttempt extends Model {
    protected $fillable = ['quiz_attempt_id','question_id','user_id','user_answer','is_correct','time_taken_sec'];
    protected $casts = ['user_answer'=>'array'];
    public function quizAttempt(): BelongsTo { return $this->belongsTo(QuizAttempt::class); }
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}


