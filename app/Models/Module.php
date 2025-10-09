<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {
    protected $fillable = ['slug','title','description','note','pass_score','is_active','user_id'];
    public function questions(){ return $this->hasMany(Question::class); }
    public function attempts(){ return $this->hasMany(QuizAttempt::class); }
    public function getRouteKeyName(): string{return 'slug';}
    public function sections(){ return $this->hasMany(\App\Models\Section::class)->orderBy('order'); }
    public function user(){ return $this->belongsTo(User::class); }
}

