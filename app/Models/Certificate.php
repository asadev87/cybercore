<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model {
  protected $fillable = ['user_id','module_id','quiz_attempt_id','serial','issued_at','pdf_path','revoked'.'code'];
  protected $casts   = ['issued_at'=>'datetime','revoked'=>'boolean'];
  public function user(){ return $this->belongsTo(User::class); }
  public function module(){ return $this->belongsTo(Module::class); }
  public function attempt(){ return $this->belongsTo(QuizAttempt::class,'quiz_attempt_id'); }
}