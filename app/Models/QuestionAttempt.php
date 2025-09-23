<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAttempt extends Model {
    protected $fillable = ['quiz_attempt_id','question_id','user_answer','is_correct','time_taken_sec'];
    protected $casts = ['user_answer'=>'array'];
    public function quizAttempt(){ return $this->belongsTo(QuizAttempt::class); }
    public function question(){ return $this->belongsTo(Question::class); }
}

