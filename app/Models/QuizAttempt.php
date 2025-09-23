<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model {
    protected $fillable = ['user_id','module_id','score','started_at','completed_at','duration_sec'];
    protected $casts = ['started_at'=>'datetime','completed_at'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
    public function module(){ return $this->belongsTo(Module::class); }
    public function questionAttempts(){ return $this->hasMany(QuestionAttempt::class); }
    public function certificate(){return $this->hasOne(\App\Models\Certificate::class);}

}


